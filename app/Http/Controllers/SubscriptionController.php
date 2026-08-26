<?php

namespace App\Http\Controllers;

use App\Models\PlanPeriod;
use App\Services\PaddleService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    public function checkout(PlanPeriod $period, PaddleService $paddle): RedirectResponse
    {
        $user = Auth::user();

        if (! $period->paddle_price_id) {
            return back()->with('error', __('Este plan no está sincronizado con Paddle aún.'));
        }

        // Si el usuario ya tiene una suscripción activa, es un cambio de plan/periodo.
        $active = $user->activeSubscription();
        if ($active && $active->paddle_subscription_id) {
            return $this->change($active, $period, $paddle);
        }

        $transaction = $paddle->createCheckout($user, $period);

        $url = $transaction['checkout']['url'] ?? null;

        if (! $url) {
            return back()->with('error', __('No se pudo generar el checkout.'));
        }

        return redirect()->away($url)->withCookie(cookie('pending_subscription', $period->id, 30));
    }

    /**
     * Cambia el plan/periodo de una suscripción activa con prorrateo.
     */
    public function change(\App\Models\Subscription $subscription, PlanPeriod $period, PaddleService $paddle): RedirectResponse
    {
        if (! $period->paddle_price_id) {
            return back()->with('error', __('Este plan no está sincronizado con Paddle aún.'));
        }

        // Si el usuario no es dueño de la suscripción, rechazar.
        if ($subscription->artist_id !== Auth::id()) {
            abort(403);
        }

        // Mismo plan y mismo período -> nada que cambiar.
        if ($subscription->plan_period_id === $period->id) {
            return back()->with('error', __('Ya tienes este plan y período.'));
        }

        $preview = $paddle->previewSubscriptionChange($subscription->paddle_subscription_id, $period);

        // Si hay cargo inmediato por la diferencia, pasar por checkout.
        $immediate = $preview['immediate_transaction'] ?? null;
        if ($immediate && isset($immediate['checkout']['url'])) {
            $url = $immediate['checkout']['url'];
            if (str_contains($url, '_ptxn=')) {
                return redirect()->away($url);
            }

            return redirect()->route('checkout.page').'?_ptxn='.$immediate['id'];
        }

        // Si no hay cargo inmediato (downgrade/crédito), aplicar directamente.
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