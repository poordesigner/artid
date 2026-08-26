<?php

namespace App\Services;

use App\Models\Artist;
use App\Models\Plan;
use App\Models\PlanPeriod;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class PaddleService
{
    private string $baseUrl;

    private string $apiKey;

    public function __construct()
    {
        $this->baseUrl = rtrim((string) config('paddle.base_url'), '/');
        $this->apiKey = (string) config('paddle.api_key');
    }

    private function client(): PendingRequest
    {
        return Http::baseUrl($this->baseUrl)
            ->withToken($this->apiKey)
            ->acceptJson();
    }

    /**
     * Crea un producto en el catálogo de Paddle.
     *
     * @return array<string, mixed>
     */
    public function createProduct(Plan $plan): array
    {
        $response = $this->client()->post('/products', [
            'name' => $plan->name,
            'description' => $plan->description ?? $plan->name,
            'tax_category' => 'saas',
            'type' => 'standard',
        ]);

        $response->throw();

        return $response->json('data');
    }

    /**
     * Crea un precio recurrente para un período de plan.
     *
     * @return array<string, mixed>
     */
    public function createPrice(PlanPeriod $period): array
    {
        $response = $this->client()->post('/prices', [
            'description' => $period->plan->name.' — '.$period->period_label,
            'name' => $period->plan->name.' ('.$period->recurrenceLabel().')',
            'product_id' => $period->paddle_product_id,
            'billing_cycle' => $period->billingCycle(),
            'unit_price' => [
                'amount' => (string) $period->priceInCents(),
                'currency_code' => 'USD',
            ],
            'quantity' => [
                'minimum' => 1,
                'maximum' => 1,
            ],
            'tax_mode' => 'account_setting',
        ]);

        $response->throw();

        return $response->json('data');
    }

    /**
     * Encuentra el Customer de Paddle por email, o lo crea si no existe.
     *
     * @return array<string, mixed>
     */
    public function findOrCreateCustomer(Artist $artist): array
    {
        $list = $this->client()
            ->get('/customers', ['email' => $artist->email])
            ->throw()
            ->json('data');

        if (! empty($list)) {
            return $list[0];
        }

        $created = $this->client()->post('/customers', [
            'name' => $artist->name,
            'email' => $artist->email,
        ]);

        $created->throw();

        return $created->json('data');
    }

    /**
     * Crea una transacción de checkout automático para un período de plan
     * y devuelve el objeto con la URL de checkout.
     *
     * @return array<string, mixed>
     */
    public function createCheckout(Artist $artist, PlanPeriod $period): array
    {
        $customer = $this->findOrCreateCustomer($artist);

        $transaction = $this->client()->post('/transactions', [
            'customer_id' => $customer['id'],
            'items' => [
                [
                    'price_id' => $period->paddle_price_id,
                    'quantity' => 1,
                ],
            ],
            'collection_mode' => 'automatic',
            'custom_data' => [
                'artist_id' => $artist->id,
                'plan_period_id' => $period->id,
            ],
        ]);

        $transaction->throw();

        return $transaction->json('data');
    }

    /**
     * Cancela una suscripción al final del período contratado.
     *
     * @return array<string, mixed>
     */
    public function cancelSubscription(string $subscriptionId): array
    {
        $response = $this->client()->post("/subscriptions/{$subscriptionId}/cancel", [
            'effective_from' => 'next_billing_period',
        ]);

        $response->throw();

        return $response->json('data');
    }

    /**
     * Remueve una cancelación programada (reactiva el plan).
     *
     * @return array<string, mixed>
     */
    public function removeScheduledCancellation(string $subscriptionId): array
    {
        $response = $this->client()->patch("/subscriptions/{$subscriptionId}", [
            'scheduled_change' => null,
        ]);

        $response->throw();

        return $response->json('data');
    }

    /**
     * Crea una sesión del customer portal para un abonado.
     *
     * @param string      $customerId     ID del customer en Paddle
     * @param array<string> $subscriptionIds subscriptions para deep links
     *
     * @return array<string, mixed>
     */
    public function createPortalSession(string $customerId, array $subscriptionIds = []): array
    {
        $body = $subscriptionIds ? ['subscription_ids' => $subscriptionIds] : [];

        $response = $this->client()->post("/customers/{$customerId}/portal-sessions", $body);

        $response->throw();

        return $response->json('data');
    }

    /**
     * Previza un cambio de plan/periodo en una suscripción existente.
     *
     * @return array<string, mixed>
     */
    public function previewSubscriptionChange(string $subscriptionId, PlanPeriod $period, string $prorationMode = 'prorated_immediately'): array
    {
        $response = $this->client()->patch("/subscriptions/{$subscriptionId}/preview", [
            'proration_billing_mode' => $prorationMode,
            'items' => [
                [
                    'price_id' => $period->paddle_price_id,
                    'quantity' => 1,
                ],
            ],
            'on_payment_failure' => 'prevent_change',
        ]);

        $response->throw();

        return $response->json('data');
    }

    /**
     * Aplica un cambio de plan/periodo en una suscripción existente
     * (upgrade/downgrade con prorrateo).
     *
     * @return array<string, mixed>
     */
    public function changeSubscriptionPlan(string $subscriptionId, PlanPeriod $period, string $prorationMode = 'prorated_immediately'): array
    {
        $response = $this->client()->patch("/subscriptions/{$subscriptionId}", [
            'proration_billing_mode' => $prorationMode,
            'items' => [
                [
                    'price_id' => $period->paddle_price_id,
                    'quantity' => 1,
                ],
            ],
            'on_payment_failure' => 'prevent_change',
        ]);

        $response->throw();

        return $response->json('data');
    }

    /**
     * Obtiene el método de pago guardado del customer (para mostrar los últimos 4 dígitos).
     *
     * @return array<string, mixed>|null
     */
    public function getPaymentMethod(string $customerId): ?array
    {
        $response = $this->client()->get("/customers/{$customerId}/payment-methods");
        $response->throw();

        $list = $response->json('data');

        foreach ($list as $method) {
            if ($method['type'] === 'card' && ! empty($method['card']['last4'])) {
                return [
                    'last4' => $method['card']['last4'],
                    'brand' => $method['card']['brand'] ?? $method['card']['type'] ?? 'tarjeta',
                    'expiry_month' => $method['card']['expiry_month'] ?? null,
                    'expiry_year' => $method['card']['expiry_year'] ?? null,
                ];
            }
        }

        return null;
    }

    /**
     * Verifica la firma de un webhook de Paddle.
     *
     * El header viene como: Paddle-Signature: ts=...;h1=...
     */
    public function verifyWebhook(string $body, ?string $signatureHeader): bool
    {
        $secret = (string) config('paddle.webhook_secret');
        if (! $secret || ! $signatureHeader) {
            return false;
        }

        $parts = [];
        foreach (explode(';', $signatureHeader) as $pair) {
            [$key, $value] = array_pad(explode('=', $pair, 2), 2, null);
            if ($value !== null) {
                $parts[$key] = $value;
            }
        }

        $ts = $parts['ts'] ?? null;
        $h1 = $parts['h1'] ?? null;

        if ($ts === null || $h1 === null) {
            return false;
        }

        // Tolerancia de replay de 5 segundos.
        $eventTime = (int) $ts;
        if (time() - $eventTime > 5) {
            return false;
        }

        $signedPayload = $ts.':'.$body;
        $expected = hash_hmac('sha256', $signedPayload, $secret);

        return hash_equals($expected, $h1);
    }
}