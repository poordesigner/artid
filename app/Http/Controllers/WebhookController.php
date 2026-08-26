<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\PlanPeriod;
use App\Models\Subscription;
use App\Models\WebhookEvent;
use App\Services\PaddleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function handle(Request $request, PaddleService $paddle): JsonResponse
    {
        $body = $request->getContent();
        $signature = $request->header('Paddle-Signature');

        if (! $paddle->verifyWebhook($body, $signature)) {
            return response()->json(['error' => 'Invalid signature'], 401);
        }

        $payload = json_decode($body, true);
        $eventId = $payload['event_id'] ?? null;
        $eventType = $payload['event_type'] ?? null;
        $data = $payload['data'] ?? [];

        Log::info('Paddle webhook recibido', [
            'event_id' => $eventId,
            'event_type' => $eventType,
            'id' => $data['id'] ?? null,
        ]);

        if (! $eventId) {
            return response()->json(['error' => 'Missing event_id'], 400);
        }

        // Idempotencia: si el evento ya se procesó, respondemos 200 sin reprocesar.
        try {
            $claimed = DB::transaction(function () use ($eventId, $eventType, $payload, $data) {
                $event = WebhookEvent::where('event_id', $eventId)->first();

                if ($event) {
                    return false;
                }

                WebhookEvent::create([
                    'event_id' => $eventId,
                    'event_type' => $eventType,
                    'occurred_at' => $payload['occurred_at'] ?? null,
                    'processed_at' => now(),
                ]);

                return true;
            });
        } catch (\Throwable $e) {
            Log::error('Error registrando evento de Paddle', [
                'event_id' => $eventId,
                'error' => $e->getMessage(),
            ]);

            return response()->json(['error' => 'Internal error'], 500);
        }

        if (! $claimed) {
            Log::info('Webhook de Paddle duplicado (omitido)', ['event_id' => $eventId]);

            return response()->json(['success' => true, 'duplicate' => true]);
        }

        try {
            $this->dispatch($eventType, $data);
        } catch (\Throwable $e) {
            Log::error('Error procesando webhook de Paddle', [
                'event_id' => $eventId,
                'event_type' => $eventType,
                'error' => $e->getMessage(),
            ]);
        }

        return response()->json(['success' => true]);
    }

    private function dispatch(?string $eventType, array $data): void
    {
        match ($eventType) {
            'subscription.created',
            'subscription.activated',
            'subscription.updated' => $this->handleSubscriptionUpsert($data),
            'subscription.trialing' => $this->handleSubscriptionStatus($data, 'trialing'),
            'subscription.canceled' => $this->handleSubscriptionStatus($data, 'canceled'),
            'subscription.paused' => $this->handleSubscriptionStatus($data, 'paused'),
            'subscription.resumed' => $this->handleSubscriptionStatus($data, 'active'),
            'subscription.past_due' => $this->handleSubscriptionStatus($data, 'past_due'),
            'transaction.completed',
            'transaction.paid' => $this->handleTransaction($data, 'completed'),
            'transaction.payment_failed' => $this->handleTransaction($data, 'failed'),
            'transaction.past_due' => $this->handleTransaction($data, 'past_due'),
            default => null,
        };
    }

    private function handleTransaction(array $data, string $status): void
    {
        $transactionId = $data['id'] ?? null;
        $subscriptionId = $data['subscription_id'] ?? null;
        $customerId = $data['customer_id'] ?? null;

        if (! $transactionId) {
            return;
        }

        $artist = null;
        $subscription = null;

        if ($subscriptionId) {
            $subscription = Subscription::where('paddle_subscription_id', $subscriptionId)->first();
            $artist = $subscription?->artist;
        }

        if (! $artist && $customerId) {
            $subscription = Subscription::where('paddle_customer_id', $customerId)->latest('id')->first();
            $artist = $subscription?->artist;
        }

        if (! $artist) {
            Log::warning('Transacción de Paddle sin artista asociado', ['transaction_id' => $transactionId]);

            return;
        }

        $totals = $data['totals'] ?? $data['details']['totals'] ?? [];
        $grandTotal = $totals['grand_total'] ?? $totals['total'] ?? 0;
        $currency = $data['currency_code'] ?? 'USD';

        Payment::updateOrCreate(
            ['paddle_transaction_id' => $transactionId],
            [
                'artist_id' => $artist->id,
                'subscription_id' => $subscription?->id,
                'status' => $status,
                'currency_code' => $currency,
                'amount' => is_numeric($grandTotal) ? $grandTotal / 100 : 0,
                'billed_at' => $data['billed_at'] ?? $data['updated_at'] ?? $data['created_at'] ?? null,
            ]
        );
    }

    private function handleSubscriptionUpsert(array $data): void
    {
        $subscriptionId = $data['id'] ?? null;
        if (! $subscriptionId) {
            return;
        }

        // Encontrar el artist por custom_data o por customer_id.
        $customData = $data['custom_data'] ?? [];
        $artist = null;

        if (! empty($customData['artist_id'])) {
            $artist = Artist::find($customData['artist_id']);
        }

        if (! $artist) {
            $customerId = $data['customer_id'] ?? null;
            $sub = Subscription::where('paddle_customer_id', $customerId)->first();
            $artist = $sub?->artist;
        }

        if (! $artist) {
            Log::warning('Webhook de Paddle sin artista asociado', ['subscription_id' => $subscriptionId]);

            return;
        }

        // Determinar plan/periodo desde los items de la suscripción.
        [$plan, $period] = $this->resolvePlanFromItems($data);

        $status = match ($data['status'] ?? null) {
            'trialing' => 'trialing',
            'paused' => 'paused',
            'canceled' => 'canceled',
            'active' => 'active',
            'past_due' => 'past_due',
            default => 'active',
        };

        $currentPeriod = $data['current_billing_period'] ?? [];
        $scheduledChange = $data['scheduled_change'] ?? null;
        $canceledAt = null;

        if ($status === 'canceled') {
            $canceledAt = $canceledAt ?? $scheduledChange['effective_at'] ?? null;
        } elseif ($scheduledChange && ($scheduledChange['action'] ?? null) === 'cancel') {
            $canceledAt = $scheduledChange['effective_at'] ?? null;
        }

        $subscription = Subscription::updateOrCreate(
            ['paddle_subscription_id' => $subscriptionId],
            [
                'artist_id' => $artist->id,
                'plan_id' => $plan?->id,
                'plan_period_id' => $period?->id,
                'paddle_customer_id' => $data['customer_id'] ?? null,
                'status' => $status,
                'next_billed_at' => $data['next_billed_at'] ?? null,
                'current_period_start' => $currentPeriod['starts_at'] ?? null,
                'current_period_end' => $currentPeriod['ends_at'] ?? null,
                'canceled_at' => $canceledAt,
            ]
        );

        // Cancelar automáticamente suscripciones viejas del mismo artista.
        if (in_array($status, ['active', 'trialing'])) {
            Subscription::where('artist_id', $artist->id)
                ->where('id', '!=', $subscription->id)
                ->whereIn('status', ['active', 'trialing', 'past_due'])
                ->update(['status' => 'canceled']);
        }

        // Aplicar límites del plan (archivar obras sobrantes si bajó de plan).
        $artist->enforcePlanLimits();
    }

    private function handleSubscriptionStatus(array $data, string $status): void
    {
        $subscriptionId = $data['id'] ?? null;
        if (! $subscriptionId) {
            return;
        }

        $subscription = Subscription::where('paddle_subscription_id', $subscriptionId)->update([
            'status' => $status,
            'next_billed_at' => $data['next_billed_at'] ?? null,
            'canceled_at' => $status === 'canceled' ? ($data['canceled_at'] ?? now()) : null,
        ]);

        // Si la suscripción se canceló (vuelve a Free) o quedó pausada, aplicar límites.
        if (in_array($status, ['canceled', 'paused'])) {
            $sub = Subscription::where('paddle_subscription_id', $subscriptionId)->first();
            $sub?->artist?->enforcePlanLimits();
        }
    }

    /**
     * @return array{0: ?Plan, 1: ?PlanPeriod}
     */
    private function resolvePlanFromItems(array $data): array
    {
        $items = $data['items'] ?? [];

        foreach ($items as $item) {
            $priceId = $item['price']['id'] ?? $item['price_id'] ?? null;

            if (! $priceId) {
                continue;
            }

            $period = PlanPeriod::where('paddle_price_id', $priceId)->first();

            if ($period) {
                return [$period->plan, $period];
            }
        }

        return [null, null];
    }
}