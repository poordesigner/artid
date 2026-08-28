<?php

namespace App\Http\Controllers;

use App\Models\TokenFunction;
use App\Models\TokenPackage;
use App\Models\TokenTransaction;
use App\Services\PaddleService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TokenController extends Controller
{
    /**
     * Muestra el saldo, el historial y los paquetes disponibles.
     */
    public function index(): View
    {
        $artist = Auth::user();

        $balance = $artist->tokenBalance();
        $transactions = TokenTransaction::where('artist_id', $artist->id)
            ->latest()
            ->limit(30)
            ->get();
        $packages = TokenPackage::active()->get();
        $tokenFunctions = TokenFunction::active()->get();

        return view('tokens.index', compact('balance', 'transactions', 'packages', 'tokenFunctions'));
    }

    /**
     * Inicia el checkout de un paquete de tokens.
     */
    public function checkout(TokenPackage $package, PaddleService $paddle): RedirectResponse
    {
        if (! $package->is_active) {
            return back()->with('error', __('Este paquete no está disponible.'));
        }

        if (! $package->paddle_price_id) {
            return back()->with('error', __('Este paquete no está sincronizado con Paddle aún.'));
        }

        $transaction = $paddle->createTokenCheckout(Auth::user(), $package);

        $url = $transaction['checkout']['url'] ?? null;

        if (! $url) {
            return back()->with('error', __('No se pudo generar el checkout.'));
        }

        return redirect()->away($url)->withCookie(cookie('pending_package', $package->id, 30));
    }
}