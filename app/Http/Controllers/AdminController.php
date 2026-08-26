<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use App\Models\Payment;
use App\Models\Plan;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function dashboard(): View
    {
        $stats = [
            'artists' => Artist::count(),
            'paid_plans' => Plan::where('is_free', false)->where('is_active', true)->count(),
            'active_subscriptions' => \App\Models\Subscription::whereIn('status', ['active', 'trialing', 'past_due'])->count(),
            'total_paid' => Payment::where('status', 'completed')->sum('amount'),
        ];

        $recentArtists = Artist::latest('created_at')->limit(5)->get();

        return view('admin.dashboard', compact('stats', 'recentArtists'));
    }
}