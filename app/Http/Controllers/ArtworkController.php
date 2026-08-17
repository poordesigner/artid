<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use App\Models\Artwork;
use App\Models\Technique;
use App\Services\GitHubService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

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
        $techniques = Technique::orderBy('name')->get();
        $seriesList = Auth::user()->series()->orderBy('name')->get();

        return view('artworks.create', compact('techniques', 'seriesList'));
    }

    /**
     * Store a newly created artwork.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->storeRules());

        $artist = $request->user();
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

        $file = $request->file('image');
        if ($file) {
            $data['image'] = $artworkId.'.'.$file->extension();
        }

        if ($artist->github_repo) {
            try {
                $this->syncCreate($artist, $artworkId, $data, $file);
            } catch (\RuntimeException $e) {
                return back()->withInput()->with('error', 'Error en GitHub: '.$e->getMessage());
            }
        }

        Artwork::create($data);

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
     * Render the QR code for the specified artwork.
     */
    public function qr(string $artwork): \Illuminate\Http\Response
    {
        $artwork = Auth::user()->artworks()->findOrFail($artwork);

        $svg = QrCode::format('svg')->size(600)->margin(2)->generate($this->publicUrl($artwork));

        return response($svg, 200, ['Content-Type' => 'image/svg+xml']);
    }

    /**
     * Serve the artwork image from the artist's repository.
     */
    public function image(string $artwork): \Illuminate\Http\Response
    {
        $artwork = Auth::user()->artworks()->findOrFail($artwork);
        $artist = Auth::user();

        if (! $artwork->image || ! $artist->github_repo) {
            abort(404);
        }

        $service = new GitHubService($artist->github_token);
        $file = $service->getFile($artist->github_repo, "artworks/{$artwork->artwork_id}/{$artwork->image}");

        if (! $file) {
            abort(404);
        }

        return response($file['content'], 200, ['Content-Type' => $this->mimeFor($artwork->image)]);
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
            $data['image'] = $artwork->artwork_id.'.'.$file->extension();
        }

        $artist = Auth::user();
        if ($artist->github_repo) {
            try {
                $this->syncUpdate($artist, $artwork, $data, $file);
            } catch (\RuntimeException $e) {
                return back()->withInput()->with('error', 'Error en GitHub: '.$e->getMessage());
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
        $artist = Auth::user();

        if ($artist->github_repo) {
            try {
                $this->syncDelete($artist, $artwork);
            } catch (\RuntimeException $e) {
                return back()->with('error', 'Error en GitHub: '.$e->getMessage());
            }
        }

        $artwork->delete();

        return redirect()->route('artworks.index')->with('status', 'Artwork deleted.');
    }

    /**
     * Commit a new artwork to the artist's repository.
     *
     * @param  array<string, mixed>  $data
     */
    private function syncCreate(Artist $artist, string $artworkId, array $data, ?UploadedFile $file): void
    {
        $service = new GitHubService($artist->github_token);
        $repo = $artist->github_repo;
        $path = "artworks/$artworkId";

        $service->putFile($repo, "$path/metadata.json", $this->metadataJson($data, $artist), "Create artwork $artworkId");

        if ($file) {
            $service->putFile($repo, "$path/{$data['image']}", (string) $file->get(), "Add image {$data['image']}");
        }

        $ids = $service->getManifest($repo);
        if (! in_array($artworkId, $ids, true)) {
            $ids[] = $artworkId;
            $service->saveManifest($repo, $ids, "Add $artworkId to manifest");
        }
    }

    /**
     * Commit artwork updates to the artist's repository.
     *
     * @param  array<string, mixed>  $data
     */
    private function syncUpdate(Artist $artist, Artwork $artwork, array $data, ?UploadedFile $file): void
    {
        $service = new GitHubService($artist->github_token);
        $repo = $artist->github_repo;
        $path = "artworks/{$artwork->artwork_id}";

        $service->putFile($repo, "$path/metadata.json", $this->metadataJson($data, $artist), "Update artwork {$artwork->artwork_id}");

        if ($file) {
            $service->putFile($repo, "$path/{$data['image']}", (string) $file->get(), "Update image {$data['image']}");

            if ($artwork->image && $artwork->image !== $data['image']) {
                $service->deleteFile($repo, "$path/{$artwork->image}", 'Remove old image');
            }
        }
    }

    /**
     * Remove an artwork from the artist's repository.
     */
    private function syncDelete(Artist $artist, Artwork $artwork): void
    {
        $service = new GitHubService($artist->github_token);
        $repo = $artist->github_repo;
        $path = "artworks/{$artwork->artwork_id}";

        $service->deleteFile($repo, "$path/metadata.json", "Delete artwork {$artwork->artwork_id}");

        if ($artwork->image) {
            $service->deleteFile($repo, "$path/{$artwork->image}", 'Delete image');
        }

        $ids = $service->getManifest($repo);
        $ids = array_values(array_diff($ids, [$artwork->artwork_id]));
        $service->saveManifest($repo, $ids, "Remove {$artwork->artwork_id}");
    }

    /**
     * Build the metadata.json content.
     *
     * @param  array<string, mixed>  $data
     */
    private function metadataJson(array $data, Artist $artist): string
    {
        $fields = [
            'artwork_id' => $data['artwork_id'],
            'title' => $data['title'],
            'artist' => '@'.($artist->github_nickname ?: $artist->name),
            'year' => $data['year'] ?? null,
            'edition' => $data['edition'] ?? null,
            'status' => $data['status'] ?? null,
            'series' => $data['series'] ?? null,
            'technique' => $data['technique'] ?? null,
            'dimensions' => $data['dimensions'] ?? null,
            'description' => $data['description'] ?? null,
            'image' => $data['image'] ?? null,
        ];

        $fields = array_filter($fields, fn ($v) => $v !== null && $v !== '');

        return json_encode($fields, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
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
            'image' => ['nullable', 'image', 'max:20480'],
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
            'image' => ['nullable', 'image', 'max:20480'],
        ];
    }

    /**
     * Shared metadata validation rules.
     */
    private function metadataRules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'year' => ['nullable', 'string', 'max:50'],
            'edition' => ['nullable', 'string', 'max:255'],
            'dimensions' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
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
     * Build the public URL encoded in the artwork's QR.
     */
    private function publicUrl(Artwork $artwork): string
    {
        $base = rtrim(env('ARTID_SHORT_DOMAIN', 'https://tatomico.s.gy'), '/');

        return $base.'?art='.$artwork->artwork_id;
    }

    /**
     * Resolve the mime type for a filename.
     */
    private function mimeFor(string $filename): string
    {
        return match (strtolower(pathinfo($filename, PATHINFO_EXTENSION))) {
            'jpg', 'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            'svg' => 'image/svg+xml',
            default => 'application/octet-stream',
        };
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
