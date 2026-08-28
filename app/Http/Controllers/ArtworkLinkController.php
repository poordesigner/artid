<?php

namespace App\Http\Controllers;

use App\Models\Artwork;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ArtworkLinkController extends Controller
{
    public function store(Request $request, Artwork $artwork): RedirectResponse
    {
        $artwork = $request->user()->artworks()->findOrFail($artwork->id);
        $artwork->load('links');

        if ($artwork->links->count() >= 10) {
            return back()->with('error', __('Máximo 10 enlaces por ficha.'));
        }

        $validated = $request->validate([
            'url' => ['required', 'url', 'max:2048'],
            'type' => ['required', 'in:video,photo,blog'],
        ]);

        $artwork->links()->create([
            'url' => $validated['url'],
            'type' => $validated['type'],
            'sort_order' => $artwork->links->count(),
        ]);

        return back()->with('status', __('Enlace agregado.'));
    }

    public function destroy(Request $request, \App\Models\ArtworkLink $link): RedirectResponse
    {
        $artwork = $request->user()->artworks()->findOrFail($link->artwork_id);

        $link->delete();

        return back()->with('status', __('Enlace eliminado.'));
    }
}