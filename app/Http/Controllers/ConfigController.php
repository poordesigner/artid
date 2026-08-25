<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ConfigController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $adminPlans = $user?->isAdmin()
            ? Plan::with(['periods', 'features', 'legalTerms'])->orderBy('sort_order')->get()
            : [];

        // Planes activos visibles para cualquier usuario logueado (comprar plan).
        $plans = Plan::with(['periods'])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('configuracion.index', [
            'user' => $user,
            'adminPlans' => $adminPlans,
            'plans' => $plans,
            'activeSubscription' => $user?->activeSubscription(),
        ]);
    }
}
