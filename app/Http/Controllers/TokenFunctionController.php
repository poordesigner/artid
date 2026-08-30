<?php

namespace App\Http\Controllers;

use App\Models\TokenAction;
use App\Models\TokenFunction;
use App\Support\SupportContext;
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
            'name' => 'required|string|max:255|unique:token_functions,name',
            'description' => 'nullable|string',
            'tokens' => 'required|integer|min:1',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
            'action_ids' => ['required', 'array', 'min:1'],
            'action_ids.*' => ['integer', 'exists:token_actions,id'],
        ]);

        $function = TokenFunction::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'tokens' => $validated['tokens'],
            'is_active' => $validated['is_active'] ?? true,
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        $function->actions()->sync($validated['action_ids']);

        SupportContext::forgetAll();

        return redirect()->route('configuracion', ['tab' => 'token-functions', 'sub' => 'functions'])
            ->with('status', __('Función de tokens creada.'));
    }

    public function update(Request $request, TokenFunction $function)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:token_functions,name,'.$function->id,
            'description' => 'nullable|string',
            'tokens' => 'required|integer|min:1',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
            'action_ids' => ['required', 'array', 'min:1'],
            'action_ids.*' => ['integer', 'exists:token_actions,id'],
        ]);

        $function->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'tokens' => $validated['tokens'],
            'is_active' => $validated['is_active'] ?? true,
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        $function->actions()->sync($validated['action_ids']);

        SupportContext::forgetAll();

        return redirect()->route('configuracion', ['tab' => 'token-functions', 'sub' => 'functions'])
            ->with('status', __('Función de tokens actualizada.'));
    }

    public function destroy(TokenFunction $function)
    {
        $function->delete();

        SupportContext::forgetAll();

        return redirect()->route('configuracion', ['tab' => 'token-functions', 'sub' => 'functions'])
            ->with('status', __('Función de tokens eliminada.'));
    }

    /* ---- Acciones (catálogo del sistema) ---- */

    public function storeAction(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:token_actions,name',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        TokenAction::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'is_active' => $validated['is_active'] ?? true,
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        SupportContext::forgetAll();

        return redirect()->route('configuracion', ['tab' => 'token-functions', 'sub' => 'actions'])
            ->with('status', __('Acción creada.'));
    }

    public function updateAction(Request $request, TokenAction $action)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:token_actions,name,'.$action->id,
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        $action->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'is_active' => $validated['is_active'] ?? true,
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        SupportContext::forgetAll();

        return redirect()->route('configuracion', ['tab' => 'token-functions', 'sub' => 'actions'])
            ->with('status', __('Acción actualizada.'));
    }

    public function destroyAction(TokenAction $action)
    {
        $action->delete();

        return redirect()->route('configuracion', ['tab' => 'token-functions', 'sub' => 'actions'])
            ->with('status', __('Acción eliminada.'));
    }
}