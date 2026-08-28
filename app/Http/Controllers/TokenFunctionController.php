<?php

namespace App\Http\Controllers;

use App\Models\TokenFunction;
use Illuminate\Http\Request;

class TokenFunctionController extends Controller
{
    public function index()
    {
        return redirect()->route('configuracion', ['tab' => 'token-functions']);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'tokens' => 'required|integer|min:1',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        TokenFunction::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'tokens' => $validated['tokens'],
            'is_active' => $validated['is_active'] ?? true,
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        return redirect()->route('configuracion', ['tab' => 'token-functions'])
            ->with('status', __('Función de tokens creada.'));
    }

    public function update(Request $request, TokenFunction $function)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'tokens' => 'required|integer|min:1',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        $function->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'tokens' => $validated['tokens'],
            'is_active' => $validated['is_active'] ?? true,
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        return redirect()->route('configuracion', ['tab' => 'token-functions'])
            ->with('status', __('Función de tokens actualizada.'));
    }

    public function destroy(TokenFunction $function)
    {
        $function->delete();

        return redirect()->route('configuracion', ['tab' => 'token-functions'])
            ->with('status', __('Función de tokens eliminada.'));
    }
}