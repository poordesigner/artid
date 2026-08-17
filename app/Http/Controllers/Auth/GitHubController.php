<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Artist;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
}
