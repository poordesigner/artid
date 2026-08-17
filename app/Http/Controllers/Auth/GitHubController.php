<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Artist;
use App\Services\GitHubService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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

        return redirect()->route('github.settings')->with('status', 'Repositorio vinculado.');
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
}
