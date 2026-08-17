<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ExhibitionController extends Controller
{
    /**
     * Store a newly created exhibition.
     */
    public function store(Request $request, string $artwork): RedirectResponse
    {
        $artwork = Auth::user()->artworks()->findOrFail($artwork);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'date' => ['nullable', 'date'],
            'description' => ['nullable', 'string'],
            'links' => ['nullable', 'string'],
        ]);

        $artwork->exhibitions()->create($validated);

        return redirect()->route('artworks.show', $artwork)->with('status', 'Exposición agregada.');
    }

    /**
     * Remove the specified exhibition.
     */
    public function destroy(string $exhibition): RedirectResponse
    {
        $exhibition = \App\Models\Exhibition::whereHas('artwork', fn ($q) => $q->where('artist_id', Auth::id()))
            ->findOrFail($exhibition);

        $artwork = $exhibition->artwork;
        $exhibition->delete();

        return redirect()->route('artworks.show', $artwork)->with('status', 'Exposición eliminada.');
    }
}
