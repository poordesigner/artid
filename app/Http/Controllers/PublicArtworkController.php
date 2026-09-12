<?php

namespace App\Http\Controllers;

use App\Models\Artwork;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicArtworkController extends Controller
{
    /**
     * Serve the public ficha of an artwork (HMAC-verified via the QR).
     */
    public function show(string $publicId, Request $request): View
    {
        $artwork = Artwork::with([
            'exhibitions' => fn ($q) => $q->oldest(),
            'ownerships' => fn ($q) => $q->oldest(),
            'links',
        ])->where('public_id', $publicId)->firstOrFail();

        if (! $artwork->verifySignature($request->query('s'))) {
            abort(404);
        }

        return view('public.artwork', compact('artwork'));
    }

    /**
     * Public profile of an artist.
     */
    public function artist(string $id): View
    {
        $artist = \App\Models\Artist::with('links')->withCount('artworks')->findOrFail($id);

        return view('public.artist', compact('artist'));
    }
}
