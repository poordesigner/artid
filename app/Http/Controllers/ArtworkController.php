<?php

namespace App\Http\Controllers;

use App\Models\Artwork;
use App\Models\Technique;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ArtworkController extends Controller
{
    /**
     * List the authenticated artist's artworks.
     */
    public function index(Request $request): View
    {
        $artist = Auth::user();

        $query = $artist->artworks();

        // Filtro por status: all | active | inactive
        $status = $request->query('status', 'all');
        if ($status === 'active') {
            $query->where('status', '!=', 'archived');
        } elseif ($status === 'inactive') {
            $query->where('status', 'archived');
        }

        // Ordenamiento: recent (default) | oldest | title
        $sort = $request->query('sort', 'recent');
        if ($sort === 'oldest') {
            $query->orderBy('created_at', 'asc');
        } elseif ($sort === 'title') {
            $query->orderBy('title', 'asc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $artworks = $query->paginate(20)->withQueryString();

        return view('artworks.index', compact('artworks', 'artist', 'status', 'sort'));
    }

    /**
     * Show the form for creating a new artwork.
     */
    public function create(): View
    {
        $techniques = Technique::orderBy('name')->get();
        $seriesList = Auth::user()->series()->orderBy('name')->get();
        $artist = Auth::user();
        $canCreate = $artist->canCreateArtwork();

        return view('artworks.create', compact('techniques', 'seriesList', 'canCreate'));
    }

    /**
     * Store a newly created artwork.
     */
    public function store(Request $request): RedirectResponse
    {
        $artist = $request->user();

        if (! $artist->canCreateArtwork()) {
            return redirect()->route('artworks.index')
                ->with('error', __('No tienes tokens disponibles. Compra un paquete de tokens para registrar más obras.'));
        }

        $validated = $request->validate($this->storeRules());

        $artworkId = $this->resolveArtworkId($validated['title'], $validated['artwork_id'] ?? null);

        $series = $validated['series_id'] ? $artist->series()->findOrFail($validated['series_id']) : null;

        $data = [
            'artist_id' => $artist->id,
            'artwork_id' => $artworkId,
            'slug' => $this->uniqueSlug($validated['title']),
            'title' => $validated['title'],
            'year' => $validated['year'] ?? null,
            'edition' => $validated['edition'] ?? null,
            'status' => 'created',
            'series_id' => $series?->id,
            'series' => $series?->name,
            'technique' => $this->techniquesToString($validated['techniques'] ?? []),
            'dimensions' => $validated['dimensions'] ?? null,
            'description' => $validated['description'] ?? null,
            'image' => null,
        ];

        $artwork = Artwork::create($data);

        // Consume 1 token por la obra (QR + ficha básica).
        if (! $artist->consumeToken('Obra: '.$artwork->title)) {
            $artwork->delete();

            return redirect()->route('artworks.index')
                ->with('error', __('No tienes tokens disponibles. Compra un paquete de tokens para registrar más obras.'));
        }

        $file = $request->file('image');
        if ($file) {
            $filename = $artworkId.'.webp';
            $optimized = $this->optimizeImage($file->getContent());
            if ($optimized !== null) {
                Storage::disk('r2')->put("artworks/$artworkId/$filename", $optimized);
                $artwork->update(['image' => $filename]);
            }
        }

        return redirect()->route('artworks.index')->with('status', 'Artwork created.');
    }

    /**
     * Show the form for editing the specified artwork.
     */
    public function edit(string $artwork): View
    {
        $artwork = Auth::user()->artworks()->findOrFail($artwork);
        $techniques = Technique::orderBy('name')->get();
        $seriesList = Auth::user()->series()->orderBy('name')->get();

        return view('artworks.edit', compact('artwork', 'techniques', 'seriesList'));
    }

    /**
     * Show the artwork's history (exhibitions + ownership).
     */
    public function show(string $artwork): View
    {
        $artwork = Auth::user()->artworks()
            ->with([
                'exhibitions' => fn ($q) => $q->latest(),
                'ownerships' => fn ($q) => $q->latest(),
                'links',
            ])
            ->findOrFail($artwork);

        return view('artworks.show', compact('artwork'));
    }

    /**
     * Render the QR code for the specified artwork.
     */
    public function qr(string $artwork): \Illuminate\Http\Response
    {
        $artwork = Auth::user()->artworks()->findOrFail($artwork);

        $svg = QrCode::format('svg')->size(600)->margin(2)->generate($artwork->signedUrl());

        return response($svg, 200, ['Content-Type' => 'image/svg+xml']);
    }

    /**
     * Update the specified artwork.
     */
    public function update(Request $request, string $artwork): RedirectResponse
    {
        $artwork = Auth::user()->artworks()->findOrFail($artwork);

        $validated = $request->validate($this->updateRules());

        $series = $validated['series_id'] ? Auth::user()->series()->findOrFail($validated['series_id']) : null;

        $data = $validated;
        unset($data['techniques']);
        $data['artwork_id'] = $artwork->artwork_id;
        $data['image'] = $artwork->image;
        $data['technique'] = $this->techniquesToString($validated['techniques'] ?? []);
        $data['series'] = $series?->name;
        $data['status'] = $artwork->status;

        $file = $request->file('image');
        if ($file) {
            $filename = $artwork->artwork_id.'.webp';
            $optimized = $this->optimizeImage($file->getContent());
            if ($optimized !== null) {
                Storage::disk('r2')->put("artworks/{$artwork->artwork_id}/$filename", $optimized);

                if ($artwork->image && $artwork->image !== $filename) {
                    Storage::disk('r2')->delete("artworks/{$artwork->artwork_id}/{$artwork->image}");
                }

                $data['image'] = $filename;
            } else {
                $data['image'] = $artwork->image;
            }
        }

        $artwork->update($data);

        return redirect()->route('artworks.index')->with('status', 'Artwork updated.');
    }

    /**
     * Remove the specified artwork.
     */
    public function destroy(string $artwork): RedirectResponse
    {
        $artwork = Auth::user()->artworks()->findOrFail($artwork);

        if ($artwork->image) {
            Storage::disk('r2')->delete("artworks/{$artwork->artwork_id}/{$artwork->image}");
        }

        $artwork->delete();

        return redirect()->route('artworks.index')->with('status', 'Artwork deleted.');
    }

    /**
     * Validation rules for creating an artwork (metadata + artwork_id + image).
     */
    private function storeRules(): array
    {
        return [
            ...$this->metadataRules(),
            'artwork_id' => ['nullable', 'string', 'max:100', 'regex:/^[A-Za-z0-9._-]+$/'],
            'series_id' => ['nullable', 'integer'],
            'techniques' => ['nullable', 'array'],
            'techniques.*' => ['string', 'max:255'],
            'image' => ['nullable', 'image', 'max:2048'],
        ];
    }

    /**
     * Validation rules for updating an artwork (metadata + image).
     */
    private function updateRules(): array
    {
        return [
            ...$this->metadataRules(),
            'series_id' => ['nullable', 'integer'],
            'techniques' => ['nullable', 'array'],
            'techniques.*' => ['string', 'max:255'],
            'image' => ['nullable', 'image', 'max:2048'],
        ];
    }

    /**
     * Shared metadata validation rules.
     */
    private function metadataRules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'year' => ['nullable', 'string', 'digits:4', 'regex:/^[12][0-9]{3}$/'],
            'edition' => ['nullable', 'string', 'max:50'],
            'dimensions' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],
        ];
    }

    /**
     * Join the selected techniques into a comma-separated string.
     *
     * @param  array<int, mixed>  $techniques
     */
    private function techniquesToString(array $techniques): ?string
    {
        $techniques = array_values(array_filter($techniques, fn ($t) => is_string($t) && $t !== ''));

        return $techniques ? implode(', ', $techniques) : null;
    }

    /**
     * Optimiza la imagen del archivo subido: redimensiona manteniendo
     * proporciones (máx 2000px), la convierte a WEBP y re-comprime hasta
     * dejarla en 300 KB o menos. Devuelve el contenido binario o null si
     * no se pudo procesar.
     */
    private function optimizeImage(string $contents): ?string
    {
        $image = @imagecreatefromstring($contents);
        if ($image === false) {
            return null;
        }

        $width = imagesx($image);
        $height = imagesy($image);
        $maxSide = 2000;

        if ($width > $maxSide || $height > $maxSide) {
            $scale = $maxSide / max($width, $height);
            $newWidth = (int) round($width * $scale);
            $newHeight = (int) round($height * $scale);

            $resized = imagecreatetruecolor($newWidth, $newHeight);
            if ($resized === false) {
                imagedestroy($image);
                return null;
            }

            imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            imagedestroy($image);
            $image = $resized;
        }

        imagealphablending($image, true);
        imagesavealpha($image, true);

        $quality = 80;
        $output = null;

        // Bajamos la calidad progresivamente hasta que pese <= 300 KB.
        while ($quality >= 40) {
            ob_start();
            imagewebp($image, null, $quality);
            $output = ob_get_clean();

            if (strlen($output) <= 300 * 1024) {
                break;
            }

            $quality -= 10;
        }

        imagedestroy($image);

        return $output !== null ? $output : null;
    }

    /**
     * Resolve a unique, permanent artwork_id (uppercase, dashes/dots).
     */
    private function resolveArtworkId(string $title, ?string $provided): string
    {
        $base = strtoupper($provided ?: Str::slug($title, '-'));
        $base = $base ?: 'OBRA';

        $id = $base;
        $counter = 1;

        while (Artwork::where('artwork_id', $id)->exists()) {
            $id = $base.'-'.($counter++);
        }

        return $id;
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
}
