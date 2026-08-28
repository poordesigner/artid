<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\TokenPackage;
use App\Models\TokenTransaction;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function dashboard(): View
    {
        $stats = [
            'artists' => Artist::count(),
            'token_packages' => TokenPackage::where('is_active', true)->count(),
            'tokens_granted' => TokenTransaction::where('type', 'purchase')
                ->sum('amount') + TokenTransaction::where('type', 'grant')->sum('amount'),
            'total_paid' => Payment::where('status', 'completed')->sum('amount'),
        ];

        $recentArtists = Artist::latest('created_at')->limit(5)->get();

        return view('admin.dashboard', compact('stats', 'recentArtists'));
    }
}