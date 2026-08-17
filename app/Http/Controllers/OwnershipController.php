<?php

namespace App\Http\Controllers;

use App\Models\Ownership;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class OwnershipController extends Controller
{
    /**
     * Store a new ownership record.
     */
    public function store(Request $request, string $artwork): RedirectResponse
    {
        $artwork = Auth::user()->artworks()->findOrFail($artwork);

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
        $ownership = Ownership::whereHas('artwork', fn ($q) => $q->where('artist_id', Auth::id()))
            ->findOrFail($ownership);

        $artwork = $ownership->artwork;
        $ownership->delete();

        return redirect()->route('artworks.show', $artwork)->with('status', 'Registro de propiedad eliminado.');
    }
}
