<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Plan;
use App\Models\TokenAction;
use App\Models\TokenFunction;
use App\Models\TokenPackage;
use App\Services\PaddleService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ConfigController extends Controller
{
    public function index(Request $request, PaddleService $paddle): View
    {
        $user = $request->user();

        $adminPlans = $user?->isAdmin()
            ? Plan::with(['periods', 'features', 'legalTerms'])->orderBy('sort_order')->get()
            : [];

        $adminPackages = $user?->isAdmin()
            ? TokenPackage::orderBy('sort_order')->get()
            : [];

        $adminTokenFunctions = $user?->isAdmin()
            ? TokenFunction::with('actions')->orderBy('sort_order')->get()
            : [];

        $adminTokenActions = $user?->isAdmin()
            ? TokenAction::orderBy('sort_order')->get()
            : [];

        $tokenFunctions = TokenFunction::active()->get();

        // Planes activos visibles para cualquier usuario logueado (comprar plan).
        $plans = Plan::with(['periods'])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        // Saldo a favor en Paddle del usuario (si tiene customer en Paddle).
        $creditBalance = 0.0;
        $activeSubscription = $user?->activeSubscription();
        if ($activeSubscription?->paddle_customer_id) {
            try {
                $creditBalance = $paddle->getCreditBalance($activeSubscription->paddle_customer_id);
            } catch (\Throwable $e) {
                $creditBalance = 0.0;
            }
        }

        return view('configuracion.index', [
            'user' => $user,
            'adminPlans' => $adminPlans,
            'adminPackages' => $adminPackages,
            'adminTokenFunctions' => $adminTokenFunctions,
            'adminTokenActions' => $adminTokenActions,
            'tokenFunctions' => $tokenFunctions,
            'plans' => $plans,
            'activeSubscription' => $activeSubscription,
            'creditBalance' => $creditBalance,
            'aiSettings' => [
                'router_model' => \App\Models\AppSetting::get('ai.router_model', (string) \App\Http\Controllers\AiConfigController::DEFAULTS['router_model']),
                'chat_model' => \App\Models\AppSetting::get('ai.chat_model', (string) \App\Http\Controllers\AiConfigController::DEFAULTS['chat_model']),
                'backup_model' => \App\Models\AppSetting::get('ai.backup_model'),
            ],
            'payments' => Payment::query()
                ->where('artist_id', $user?->id)
                ->latest('billed_at')
                ->limit(20)
                ->get(),
        ]);
    }
}