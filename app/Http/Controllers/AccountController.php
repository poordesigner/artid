<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use App\Models\Plan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index(): View
    {
        $artists = Artist::with(['activeSubscription' => fn ($q) => $q->with('plan')])
            ->withCount('artworks')
            ->orderBy('name')
            ->get();

        $plans = Plan::where('is_active', true)->orderBy('sort_order')->get();

        return view('configuracion.cuentas', compact('artists', 'plans'));
    }

    public function grant(Request $request, Artist $artist): RedirectResponse
    {
        $validated = $request->validate([
            'plan_id' => ['required', 'exists:plans,id'],
            'duration' => ['required', 'in:7,30,90,none'],
        ]);

        $expiresAt = match ($validated['duration']) {
            '7' => now()->addDays(7),
            '30' => now()->addDays(30),
            '90' => now()->addDays(90),
            default => null,
        };

        $artist->update([
            'granted_plan_id' => $validated['plan_id'],
            'granted_expires_at' => $expiresAt,
        ]);

        // Aplicar/archivar obras según el nuevo límite.
        $artist->enforcePlanLimits();

        return back()->with('status', __('Plan otorgado a :name.', ['name' => $artist->name]));
    }

    public function revoke(Artist $artist): RedirectResponse
    {
        $artist->update([
            'granted_plan_id' => null,
            'granted_expires_at' => null,
        ]);

        $artist->enforcePlanLimits();

        return back()->with('status', __('Grant revocado a :name.', ['name' => $artist->name]));
    }

    public function toggleAdmin(Artist $artist): RedirectResponse
    {
        // No permitir quitarle el admin a uno mismo.
        if ($artist->id === auth()->id()) {
            return back()->with('error', __('No puedes cambiar tu propio rol de administrador.'));
        }

        $artist->update(['is_admin' => ! $artist->is_admin]);

        return back()->with('status', $artist->is_admin
            ? __(':name ahora es administrador.', ['name' => $artist->name])
            : __(':name ya no es administrador.', ['name' => $artist->name]));
    }
}