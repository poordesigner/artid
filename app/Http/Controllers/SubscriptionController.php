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

        $transaction = $paddle->createCheckout($user, $period);

        $url = $transaction['checkout']['url'] ?? null;

        if (! $url) {
            return back()->with('error', __('No se pudo generar el checkout.'));
        }

        return redirect()->away($url)->withCookie(cookie('pending_subscription', $period->id, 30));
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