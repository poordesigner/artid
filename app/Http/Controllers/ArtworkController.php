<?php

namespace App\Http\Controllers;

use App\Models\Artwork;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ArtworkController extends Controller
{
    /**
     * List the authenticated artist's artworks.
     */
    public function index(): View
    {
        $artworks = Auth::user()->artworks()->latest()->paginate(20);

        return view('artworks.index', compact('artworks'));
    }

    /**
     * Show the form for creating a new artwork.
     */
    public function create(): View
    {
        return view('artworks.create');
    }

    /**
     * Store a newly created artwork.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->rules());

        $request->user()->artworks()->create([
            ...$validated,
            'slug' => $this->uniqueSlug($validated['title']),
            'artwork_id' => $this->uniqueArtworkId(),
        ]);

        return redirect()->route('artworks.index')->with('status', 'Artwork created.');
    }

    /**
     * Show the form for editing the specified artwork.
     */
    public function edit(string $artwork): View
    {
        $artwork = Auth::user()->artworks()->findOrFail($artwork);

        return view('artworks.edit', compact('artwork'));
    }

    /**
     * Update the specified artwork.
     */
    public function update(Request $request, string $artwork): RedirectResponse
    {
        $artwork = Auth::user()->artworks()->findOrFail($artwork);

        $artwork->update($request->validate($this->rules()));

        return redirect()->route('artworks.index')->with('status', 'Artwork updated.');
    }

    /**
     * Remove the specified artwork.
     */
    public function destroy(string $artwork): RedirectResponse
    {
        Auth::user()->artworks()->findOrFail($artwork)->delete();

        return redirect()->route('artworks.index')->with('status', 'Artwork deleted.');
    }

    /**
     * Validation rules shared between store and update.
     */
    private function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'year' => ['nullable', 'string', 'max:50'],
            'edition' => ['nullable', 'string', 'max:255'],
            'status' => ['required', Rule::in(Artwork::STATUSES)],
            'series' => ['nullable', 'string', 'max:255'],
            'technique' => ['nullable', 'string', 'max:255'],
            'dimensions' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'location' => ['nullable', 'string', 'max:255'],
            'owner' => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * Generate a unique, stable slug from the title.
     */
    private function uniqueSlug(string $title): string
    {
        $base = Str::slug($title) ?: 'obra';

        $slug = $base;
        $counter = 1;

        while (Artwork::where('slug', $slug)->exists()) {
            $slug = $base.'-'.($counter++);
        }

        return $slug;
    }

    /**
     * Generate a unique permanent identifier for the artwork.
     */
    private function uniqueArtworkId(): string
    {
        do {
            $code = strtoupper(Str::random(12));
        } while (Artwork::where('artwork_id', $code)->exists());

        return $code;
    }
}
