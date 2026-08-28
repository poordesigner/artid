<?php

namespace App\Http\Controllers;

use App\Models\ArtistLink;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ArtistLinkController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $artist = $request->user();

        if ($artist->links()->count() >= 5) {
            return back()->with('error', __('Máximo 5 enlaces por perfil.'));
        }

        $validated = $request->validate([
            'url' => ['required', 'url', 'max:2048'],
            'type' => ['required', 'in:portfolio,cv,exhibitions'],
        ]);

        $artist->links()->create([
            'url' => $validated['url'],
            'type' => $validated['type'],
            'sort_order' => $artist->links()->count(),
        ]);

        return back()->with('status', __('Enlace agregado.'));
    }

    public function destroy(Request $request, ArtistLink $link): RedirectResponse
    {
        if ($link->artist_id !== $request->user()->id) {
            abort(403);
        }

        $link->delete();

        return back()->with('status', __('Enlace eliminado.'));
    }
}