<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Artist;
use App\Models\Artwork;
use App\Services\GitHubService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Laravel\Socialite\Facades\Socialite;

class GitHubController extends Controller
{
    /**
     * Redirect the user to the GitHub OAuth page.
     */
    public function redirect(): RedirectResponse
    {
        return Socialite::driver('github')
            ->scopes(['repo', 'user:email', 'read:user'])
            ->redirect();
    }

    /**
     * Link the authenticated artist with their GitHub account.
     */
    public function callback(Request $request): RedirectResponse
    {
        $githubUser = Socialite::driver('github')->user();

        $taken = Artist::query()
            ->where('github_id', $githubUser->getId())
            ->whereKeyNot($request->user()->id)
            ->exists();

        if ($taken) {
            return redirect()->route('dashboard')
                ->with('error', 'Esa cuenta de GitHub ya está vinculada a otro artista.');
        }

        $request->user()->forceFill([
            'github_id' => $githubUser->getId(),
            'github_token' => $githubUser->token,
            'github_nickname' => $githubUser->getNickname(),
        ])->save();

        return redirect()->route('dashboard')->with('status', 'GitHub conectado.');
    }

    /**
     * Show the repository configuration (link existing / create new).
     */
    public function settings(Request $request): View
    {
        $artist = $request->user();

        $repos = [];
        if ($artist->github_token) {
            $repos = (new GitHubService($artist->github_token))->listRepos();
        }

        return view('github.settings', compact('repos'));
    }

    /**
     * Link an existing repository to the artist.
     */
    public function linkRepo(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'repo' => ['required', 'string', 'max:255', 'regex:/^[A-Za-z0-9_.-]+\/[A-Za-z0-9_.-]+$/'],
        ]);

        $request->user()->update(['github_repo' => $validated['repo']]);

        $imported = $this->importArtworks($request->user());

        return redirect()->route('github.settings')
            ->with('status', "Repositorio vinculado. {$imported} obras importadas.");
    }

    /**
     * Create a new repository and link it to the artist.
     */
    public function createRepo(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'regex:/^[A-Za-z0-9._-]+$/'],
        ]);

        $artist = $request->user();

        try {
            $fullName = (new GitHubService($artist->github_token))->createRepo($validated['name']);
        } catch (\RuntimeException $e) {
            return redirect()->route('github.settings')->with('error', $e->getMessage());
        }

        $artist->update(['github_repo' => $fullName]);

        return redirect()->route('github.settings')->with('status', 'Repositorio creado y vinculado.');
    }

    /**
     * Re-sync the local cache with the artworks in the linked repository.
     */
    public function sync(Request $request): RedirectResponse
    {
        $artist = $request->user();

        if (! $artist->github_repo) {
            return redirect()->route('github.settings')->with('error', 'Primero vinculá un repositorio.');
        }

        try {
            $imported = $this->importArtworks($artist);
        } catch (\RuntimeException $e) {
            return redirect()->route('github.settings')->with('error', 'Error en GitHub: '.$e->getMessage());
        }

        return redirect()->route('artworks.index')->with('status', "Sincronizado: {$imported} obras.");
    }

    /**
     * Install the open-source "ficha" files into the artist's repository.
     */
    public function syncFicha(Request $request): RedirectResponse
    {
        $artist = $request->user();

        if (! $artist->github_repo) {
            return redirect()->route('github.settings')->with('error', 'Primero vinculá un repositorio.');
        }

        $service = new GitHubService($artist->github_token);
        $repo = $artist->github_repo;

        $files = [
            'index.html' => 'index.html',
            'redirect.html' => 'redirect.html',
            'css/style.css' => 'css/style.css',
            'js/app.js' => 'js/app.js',
        ];

        try {
            foreach ($files as $local => $remote) {
                $path = base_path('ficha/'.$local);

                if (! is_file($path)) {
                    continue;
                }

                $service->putFile($repo, $remote, (string) file_get_contents($path), "Sync ficha: $remote");
            }
        } catch (\RuntimeException $e) {
            return redirect()->route('github.settings')->with('error', 'Error en GitHub: '.$e->getMessage());
        }

        return redirect()->route('github.settings')->with('status', 'Ficha instalada en tu repositorio.');
    }

    /**
     * Import the repository's artworks into the local cache.
     */
    private function importArtworks(Artist $artist): int
    {
        $service = new GitHubService($artist->github_token);
        $repo = $artist->github_repo;

        $ids = $service->listArtworkIds($repo);
        $imported = 0;

        foreach ($ids as $id) {
            $file = $service->getFile($repo, "artworks/$id/metadata.json");

            if (! $file) {
                continue;
            }

            $meta = json_decode($file['content'], true);

            if (! is_array($meta)) {
                continue;
            }

            $artwork = $artist->artworks()->firstOrNew(['artwork_id' => $id]);

            $slug = Str::slug($meta['title'] ?? $id) ?: 'obra';
            if (Artwork::where('slug', $slug)->where('id', '!=', $artwork->id)->exists()) {
                $slug = $slug.'-'.Str::lower($id);
            }

            $seriesName = $meta['series'] ?? null;
            $seriesId = $seriesName
                ? $artist->series()->firstOrCreate(['name' => $seriesName])->id
                : null;

            $artwork->fill([
                'title' => $meta['title'] ?? $id,
                'slug' => $artwork->slug ?: $slug,
                'year' => isset($meta['year']) ? (string) $meta['year'] : null,
                'edition' => $meta['edition'] ?? null,
                'status' => in_array($meta['status'] ?? 'created', Artwork::STATUSES, true) ? $meta['status'] : 'created',
                'series' => $seriesName,
                'series_id' => $seriesId,
                'technique' => $meta['technique'] ?? null,
                'dimensions' => $meta['dimensions'] ?? null,
                'description' => $meta['description'] ?? null,
                'location' => $meta['location'] ?? null,
                'owner' => $meta['owner'] ?? null,
                'image' => $meta['image'] ?? null,
            ]);

            $artwork->save();
            $imported++;
        }

        return $imported;
    }
}
