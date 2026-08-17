<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SeriesController extends Controller
{
    /**
     * List the authenticated artist's series.
     */
    public function index(): View
    {
        $series = Auth::user()->series()->withCount('artworks')->latest()->get();

        return view('series.index', compact('series'));
    }

    /**
     * Store a newly created series.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $request->user()->series()->create($validated);

        return redirect()->route('series.index')->with('status', 'Serie creada.');
    }

    /**
     * Update the specified series.
     */
    public function update(Request $request, string $series): RedirectResponse
    {
        $series = Auth::user()->series()->findOrFail($series);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $series->update($validated);

        return redirect()->route('series.index')->with('status', 'Serie actualizada.');
    }

    /**
     * Remove the specified series.
     */
    public function destroy(string $series): RedirectResponse
    {
        Auth::user()->series()->findOrFail($series)->delete();

        return redirect()->route('series.index')->with('status', 'Serie eliminada.');
    }
}
