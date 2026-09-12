<?php

namespace App\Http\Controllers;

use App\Models\Ownership;
use App\Services\GitHubService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class OwnershipController extends Controller
{
    /**
     * Show the form for adding an ownership record.
     */
    public function create(string $artwork): View
    {
        $artwork = Auth::user()->artworks()->findOrFail($artwork);

        return view('ownerships.create', compact('artwork'));
    }

    /**
     * Store a new ownership record.
     */
    public function store(Request $request, string $artwork): RedirectResponse
    {
        $artist = Auth::user();
        $artwork = $artist->artworks()->findOrFail($artwork);

        $validated = $request->validate([
            'type' => ['required', Rule::in(['initial', 'transfer'])],
            'owner_name' => ['required', 'string', 'max:255'],
            'owner_email' => ['nullable', 'email', 'max:255'],
            'transferred_at' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
        ]);

        $secretKey = null;
        $secretKeyHash = null;

        if ($validated['type'] === 'transfer') {
            $secretKey = strtoupper(Str::random(16));
            $secretKeyHash = Hash::make($secretKey);
        }

        $artwork->ownerships()->create([
            'type' => $validated['type'],
            'owner_name' => $validated['owner_name'],
            'owner_email' => $validated['owner_email'] ?? null,
            'transferred_at' => $validated['transferred_at'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'secret_key_hash' => $secretKeyHash,
        ]);

        $this->syncToGitHub($artist, $artwork);

        if ($secretKey) {
            return redirect()->route('artworks.show', $artwork)
                ->with('secret_key', $secretKey)
                ->with('status', 'Registro de propiedad creado. Guardá la llave secreta.');
        }

        return redirect()->route('artworks.show', $artwork)->with('status', 'Registro de propiedad creado.');
    }

    /**
     * Reveal the owner info using the secret key.
     */
    public function reveal(Request $request, string $ownership): RedirectResponse
    {
        $ownership = Ownership::whereHas('artwork', fn ($q) => $q->where('artist_id', Auth::id()))
            ->findOrFail($ownership);

        $key = strtoupper(trim((string) $request->input('secret_key')));

        if (! $ownership->secret_key_hash || ! Hash::check($key, $ownership->secret_key_hash)) {
            return back()->with('error', 'Llave secreta inválida.');
        }

        return back()->with('revealed', [
            'name' => $ownership->owner_name,
            'email' => $ownership->owner_email,
            'date' => $ownership->transferred_at?->format('Y-m-d'),
        ]);
    }

    /**
     * Remove the specified ownership record.
     */
    public function destroy(string $ownership): RedirectResponse
    {
        $artist = Auth::user();

        $ownership = Ownership::whereHas('artwork', fn ($q) => $q->where('artist_id', $artist->id))
            ->findOrFail($ownership);

        $artwork = $ownership->artwork;
        $ownership->delete();

        $this->syncToGitHub($artist, $artwork);

        return redirect()->route('artworks.show', $artwork)->with('status', 'Registro de propiedad eliminado.');
    }

    /**
     * Write the artwork's ownership chain to the repository (public copy).
     */
    private function syncToGitHub($artist, $artwork): void
    {
        if (! $artist->github_repo) {
            return;
        }

        try {
            $service = new GitHubService($artist->github_token);

            $data = $artwork->ownerships()->oldest()->get()
                ->map(fn ($o) => [
                    'type' => $o->type,
                    'owner' => $o->type === 'initial' ? $o->owner_name : null,
                    'date' => $o->transferred_at?->format('Y-m-d'),
                    'notes' => $o->notes,
                ])->values()->all();

            $service->putFile(
                $artist->github_repo,
                "artworks/{$artwork->artwork_id}/ownership.json",
                json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                'Update ownership'
            );
        } catch (\RuntimeException) {
            // best-effort
        }
    }
}
