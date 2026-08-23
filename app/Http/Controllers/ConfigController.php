<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ConfigController extends Controller
{
    public function index(Request $request): View
    {
        $plans = [];

        if ($request->user()?->isAdmin()) {
            $plans = Plan::with(['periods', 'features', 'legalTerms'])
                ->orderBy('sort_order')
                ->get();
        }

        return view('configuracion.index', [
            'user' => $request->user(),
            'plans' => $plans,
        ]);
    }
}
