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

        return redirect()->away($url);
    }
}