<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function artist(): View
    {
        $user = Auth::user();

        $artworkCount = $user->activeArtworksCount();
        $max = $user->currentMaxArtworks();
        $totalArtworks = $user->artworks()->count();
        $seriesCount = $user->series()->count();
        $subscription = $user->activeSubscription();
        $recentArtworks = $user->artworks()->latest()->limit(4)->get();

        return view('dashboard.artist', compact(
            'artworkCount',
            'max',
            'totalArtworks',
            'seriesCount',
            'subscription',
            'recentArtworks'
        ));
    }
}