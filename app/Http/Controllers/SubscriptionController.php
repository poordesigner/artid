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

        $preview = $paddle->previewSubscriptionChange($subscription->paddle_subscription_id, $newPeriod);

        $summary = $preview['update_summary'] ?? [];
        $immediate = $preview['immediate_transaction'] ?? null;

        $amounts = [
            'credit' => $this->toDollars($summary['credit']['amount'] ?? 0),
            'charge' => $this->toDollars($summary['charge']['amount'] ?? 0),
            'to_action' => $this->toDollars($summary['result']['amount'] ?? 0),
            'action' => $summary['result']['action'] ?? 'none', // charge | credit | none
        ];

        $currentPlan = $subscription->plan;
        $targetPlan = $newPeriod->plan;

        return view('subscriptions.confirm-change', compact(
            'subscription',
            'currentPlan',
            'newPeriod',
            'targetPlan',
            'amounts',
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

        $data = $paddle->changeSubscriptionPlan($subscription->paddle_subscription_id, $period);

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

    public function portal(PaddleService $paddle): RedirectResponse
    {
        $user = Auth::user();
        $subscription = $user->activeSubscription();

        if (! $subscription || ! $subscription->paddle_customer_id) {
            return back()->with('error', __('No tienes una suscripción activa para gestionar.'));
        }

        $session = $paddle->createPortalSession(
            $subscription->paddle_customer_id,
            $subscription->paddle_subscription_id ? [$subscription->paddle_subscription_id] : []
        );

        $url = $session['urls']['general']['overview'] ?? null;

        if (! $url) {
            return back()->with('error', __('No se pudo generar el portal de suscripción.'));
        }

        return redirect()->away($url);
    }
}