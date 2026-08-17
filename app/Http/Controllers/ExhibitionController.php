<?php

namespace App\Http\Controllers;

use App\Models\Exhibition;
use App\Services\GitHubService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ExhibitionController extends Controller
{
    /**
     * Show the form for adding an exhibition.
     */
    public function create(string $artwork): View
    {
        $artwork = Auth::user()->artworks()->findOrFail($artwork);

        return view('exhibitions.create', compact('artwork'));
    }

    /**
     * Store a newly created exhibition.
     */
    public function store(Request $request, string $artwork): RedirectResponse
    {
        $artist = Auth::user();
        $artwork = $artist->artworks()->findOrFail($artwork);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'date' => ['nullable', 'date'],
            'description' => ['nullable', 'string'],
            'links' => ['nullable', 'string'],
        ]);

        $artwork->exhibitions()->create($validated);

        $this->syncToGitHub($artist, $artwork);

        return redirect()->route('artworks.show', $artwork)->with('status', 'Exposición agregada.');
    }

    /**
     * Remove the specified exhibition.
     */
    public function destroy(string $exhibition): RedirectResponse
    {
        $artist = Auth::user();

        $exhibition = Exhibition::whereHas('artwork', fn ($q) => $q->where('artist_id', $artist->id))
            ->findOrFail($exhibition);

        $artwork = $exhibition->artwork;
        $exhibition->delete();

        $this->syncToGitHub($artist, $artwork);

        return redirect()->route('artworks.show', $artwork)->with('status', 'Exposición eliminada.');
    }

    /**
     * Write the artwork's exhibitions to the repository (public copy).
     */
    private function syncToGitHub($artist, $artwork): void
    {
        if (! $artist->github_repo) {
            return;
        }

        try {
            $service = new GitHubService($artist->github_token);

            $data = $artwork->exhibitions()->oldest()->get()
                ->map(fn ($e) => [
                    'name' => $e->name,
                    'date' => $e->date?->format('Y-m-d'),
                    'description' => $e->description,
                    'links' => $e->links,
                ])->values()->all();

            $service->putFile(
                $artist->github_repo,
                "artworks/{$artwork->artwork_id}/exhibitions.json",
                json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                'Update exhibitions'
            );
        } catch (\RuntimeException) {
            // best-effort
        }
    }
}
