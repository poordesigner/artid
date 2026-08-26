<?php

namespace App\Http\Controllers;

use App\Models\PlanPeriod;
use App\Services\PaddleService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SubscriptionController extends Controller
{
    public function checkout(PlanPeriod $period, PaddleService $paddle): RedirectResponse|View
    {
        $user = Auth::user();

        if (! $period->paddle_price_id) {
            return back()->with('error', __('Este plan no está sincronizado con Paddle aún.'));
        }

        // Si el usuario ya tiene una suscripción activa, mostrar confirmación de cambio.
        $active = $user->activeSubscription();
        if ($active && $active->paddle_subscription_id) {
            return $this->showChangeConfirmation($active, $period, $paddle);
        }

        $transaction = $paddle->createCheckout($user, $period);

        $url = $transaction['checkout']['url'] ?? null;

        if (! $url) {
            return back()->with('error', __('No se pudo generar el checkout.'));
        }

        return redirect()->away($url)->withCookie(cookie('pending_subscription', $period->id, 30));
    }

    /**
     * Muestra la confirmación del cambio de plan con los montos prorrateados.
     */
    public function showChangeConfirmation(\App\Models\Subscription $subscription, PlanPeriod $newPeriod, PaddleService $paddle): View
    {
        if ($subscription->artist_id !== Auth::id()) {
            abort(403);
        }

        if ($subscription->plan_period_id === $newPeriod->id) {
            return back()->with('error', __('Ya tienes este plan y período.'));
        }

        // Hacer el preview con cobro inmediato para saber el monto.
        $preview = $paddle->previewSubscriptionChange($subscription->paddle_subscription_id, $newPeriod);

        $summary = $preview['update_summary'] ?? [];
        $immediate = $preview['immediate_transaction'] ?? null;

        $immediateTotals = $immediate['details']['totals'] ?? [];
        $chargeCents = $immediateTotals['grand_total'] ?? $summary['charge']['amount'] ?? 0;
        $action = $summary['result']['action'] ?? 'none'; // charge | credit | none

        // Decidir si el cobro se difiere a la próxima factura (monto < mínimo).
        $minImmediate = (float) config('paddle.min_immediate_charge', 10);
        $deferred = $action === 'charge' && ((float) $chargeCents / 100) < $minImmediate;
        $mode = $deferred ? 'prorated_next_billing_period' : 'prorated_immediately';

        // Si se difiere, re-preview con ese modo para mostrar el monto real futuro.
        if ($deferred) {
            $preview = $paddle->previewSubscriptionChange($subscription->paddle_subscription_id, $newPeriod, $mode);
            $summary = $preview['update_summary'] ?? [];
            $immediate = $preview['immediate_transaction'] ?? null;
            $immediateTotals = $immediate['details']['totals'] ?? [];
            $chargeCents = $immediateTotals['grand_total'] ?? $summary['charge']['amount'] ?? 0;
        }

        $amounts = [
            'credit' => $this->toDollars($summary['credit']['amount'] ?? 0),
            'charge' => $this->toDollars($chargeCents),
            'to_action' => $this->toDollars($chargeCents),
            'action' => $action,
            'deferred' => $deferred,
            'min_immediate' => number_format($minImmediate, 2),
        ];

        // Rango del período actual y días restantes para explicar el prorrateo.
        $periodStart = $subscription->current_period_start ?? $subscription->startedAt();
        $periodEnd = $subscription->current_period_end ?? $subscription->next_billed_at;
        $now = now();

        $usedDays = $periodStart ? (int) round($periodStart->diffInDays($now)) : 0;
        $restDays = $periodEnd ? (int) round($now->diffInDays($periodEnd)) : 0;
        $totalDays = max(1, $usedDays + $restDays);

        $proration = [
            'period_start' => $periodStart?->format('d/m/Y'),
            'period_end' => $periodEnd?->format('d/m/Y'),
            'today' => $now->format('d/m/Y'),
            'total_days' => $totalDays,
            'rest_days' => $restDays,
            'used_days' => $usedDays,
        ];

        $currentPlan = $subscription->plan;
        $targetPlan = $newPeriod->plan;

        // Método de pago guardado para dar claridad sobre dónde se cobra.
        $paymentMethod = null;
        if ($subscription->paddle_customer_id) {
            try {
                $paymentMethod = $paddle->getPaymentMethod($subscription->paddle_customer_id);
            } catch (\Throwable $e) {
                $paymentMethod = null;
            }
        }

        return view('subscriptions.confirm-change', compact(
            'subscription',
            'currentPlan',
            'newPeriod',
            'targetPlan',
            'amounts',
            'proration',
            'paymentMethod',
        ));
    }

    /**
     * Aplica el cambio de plan (upgrade/downgrade) tras la confirmación.
     */
    public function change(PlanPeriod $period, PaddleService $paddle): RedirectResponse
    {
        $user = Auth::user();
        $subscription = $user->activeSubscription();

        if (! $subscription || ! $subscription->paddle_subscription_id) {
            return back()->with('error', __('No tienes una suscripción activa.'));
        }

        if ($subscription->plan_period_id === $period->id) {
            return back()->with('error', __('Ya tienes este plan y período.'));
        }

        // Degregar el modo de prorrateo según el monto de la diferencia.
        $mode = $this->resolveProrationMode($subscription, $period, $paddle);

        try {
            $data = $paddle->changeSubscriptionPlan($subscription->paddle_subscription_id, $period, $mode);
        } catch (\Illuminate\Http\Client\RequestException $e) {
            // Si el cobro falló, el plan no cambió. Avisar y sugerir actualizar método de pago.
            return redirect()->route('configuracion', ['tab' => 'mi-plan'])
                ->with('error', __('No pudimos cobrar la diferencia a tu método de pago y el plan no cambió. Actualizá tu método de pago desde "Gestionar suscripción" e intentá de nuevo.'));
        }

        $subscription->update([
            'plan_id' => $period->plan_id,
            'plan_period_id' => $period->id,
            'status' => $data['status'] ?? $subscription->status,
            'next_billed_at' => $data['next_billed_at'] ?? $subscription->next_billed_at,
            'current_period_start' => $data['current_billing_period']['starts_at'] ?? $subscription->current_period_start,
            'current_period_end' => $data['current_billing_period']['ends_at'] ?? $subscription->current_period_end,
        ]);

        return redirect()->route('configuracion', ['tab' => 'mi-plan'])
            ->with('status', __('Cambiaste tu plan correctamente.'));
    }

    /**
     * Decide el modo de prorrateo según el monto de la diferencia:
     * si el cargo es menor al mínimo, se difiere a la próxima factura.
     */
    private function resolveProrationMode(\App\Models\Subscription $subscription, PlanPeriod $newPeriod, PaddleService $paddle): string
    {
        $preview = $paddle->previewSubscriptionChange($subscription->paddle_subscription_id, $newPeriod);

        $summary = $preview['update_summary'] ?? [];
        $immediate = $preview['immediate_transaction'] ?? null;
        $immediateTotals = $immediate['details']['totals'] ?? [];
        $chargeCents = $immediateTotals['grand_total'] ?? $summary['charge']['amount'] ?? 0;
        $action = $summary['result']['action'] ?? 'none';

        $minImmediate = (float) config('paddle.min_immediate_charge', 10);
        $deferred = $action === 'charge' && ((float) $chargeCents / 100) < $minImmediate;

        return $deferred ? 'prorated_next_billing_period' : 'prorated_immediately';
    }

    private function toDollars($cents): string
    {
        return number_format((float) $cents / 100, 2);
    }

    public function cancel(PaddleService $paddle): RedirectResponse
    {
        $user = Auth::user();
        $subscription = $user->activeSubscription();

        if (! $subscription || ! $subscription->paddle_subscription_id) {
            return back()->with('error', __('No tienes una suscripción activa para cancelar.'));
        }

        $data = $paddle->cancelSubscription($subscription->paddle_subscription_id);

        $subscription->update([
            'canceled_at' => $data['scheduled_change']['effective_at'] ?? null,
            'next_billed_at' => $data['next_billed_at'] ?? $subscription->next_billed_at,
        ]);

        return back()->with('status', __('Tu plan quedará cancelado al final del período contratado.'));
    }

    public function reactivate(PaddleService $paddle): RedirectResponse
    {
        $user = Auth::user();
        $subscription = $user->activeSubscription();

        if (! $subscription || ! $subscription->paddle_subscription_id) {
            return back()->with('error', __('No tienes una suscripción activa para reactivar.'));
        }

        // Solo se puede reactivar si hay una cancelación programada (dentro del período).
        if (! $subscription->hasScheduledCancellation()) {
            return back()->with('error', __('Tu plan no tiene una cancelación programada.'));
        }

        $data = $paddle->removeScheduledCancellation($subscription->paddle_subscription_id);

        $subscription->update([
            'canceled_at' => null,
            'status' => $data['status'] ?? $subscription->status,
            'next_billed_at' => $data['next_billed_at'] ?? $subscription->next_billed_at,
        ]);

        return back()->with('status', __('Reactivate tu plan correctamente. Seguís vigente sin cambios.'));
    }

    public function portal(PaddleService $paddle): \Illuminate\Http\JsonResponse
    {
        $user = Auth::user();
        $subscription = $user->activeSubscription();

        if (! $subscription || ! $subscription->paddle_customer_id) {
            return response()->json(['error' => __('No tienes una suscripción activa para gestionar.')], 422);
        }

        $session = $paddle->createPortalSession(
            $subscription->paddle_customer_id,
            $subscription->paddle_subscription_id ? [$subscription->paddle_subscription_id] : []
        );

        $url = $session['urls']['general']['overview'] ?? null;

        if (! $url) {
            return response()->json(['error' => __('No se pudo generar el portal de suscripción.')], 422);
        }

        return response()->json(['url' => $url]);
    }
}