<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use App\Models\Plan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function index(): View
    {
        $artists = Artist::with('links')
            ->withCount('artworks')
            ->orderBy('name')
            ->get();

        $plans = Plan::where('is_active', true)->orderBy('sort_order')->get();

        return view('configuracion.cuentas', compact('artists', 'plans'));
    }

    public function grant(Request $request, Artist $artist): RedirectResponse
    {
        $validated = $request->validate([
            'token_amount' => ['required', 'integer', 'min:1', 'max:100000'],
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        $artist->addTokens($validated['token_amount'], 'grant', null, $validated['note'] ?: 'Tokens otorgados por el administrador');

        return back()->with('status', __(':count tokens otorgados a :name.', [
            'count' => $validated['token_amount'],
            'name' => $artist->name,
        ]));
    }

    public function revoke(Artist $artist): RedirectResponse
    {
        // Marcador: en el modelo de tokens no hay plan que revocar.
        return back()->with('error', __('El acceso por plan ya no se usa. Usa el otorgamiento de tokens.'));
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