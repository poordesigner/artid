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
        $totalArtworks = $user->artworks()->count();
        $seriesCount = $user->series()->count();
        $tokenBalance = $user->tokenBalance();
        $canCreate = $user->canCreateArtwork();
        $recentArtworks = $user->artworks()->latest()->limit(4)->get();

        return view('dashboard.artist', compact(
            'artworkCount',
            'totalArtworks',
            'seriesCount',
            'tokenBalance',
            'canCreate',
            'recentArtworks'
        ));
    }
}