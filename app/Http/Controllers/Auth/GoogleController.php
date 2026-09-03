<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Artist;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;

class GoogleController extends Controller
{
    /**
     * Redirect the user to the Google OAuth page.
     */
    public function redirect(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     *
     * Si el state de OAuth no coincide (InvalidStateException), se reinicia el
     * flujo en lugar de devolver un 500 — ocurre con caché de proxy, o cuando
     * el callback se reabre con un state ya consumido.
     */
    public function callback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (InvalidStateException $e) {
            return redirect()->route('login')
                ->with('status', __('Hubo un problema al iniciar sesión con Google. Inténtalo de nuevo.'));
        }

        $artist = Artist::query()
            ->where('google_id', $googleUser->getId())
            ->orWhere('email', $googleUser->getEmail())
            ->first();

        $request = request();
        $legalVersion = config('artid.legal_version');
        $ip = $request->ip();
        $ua = $request->userAgent();

        if (! $artist) {
            $artist = Artist::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'email_verified_at' => now(),
                'terms_accepted_at' => now(),
                'terms_version' => $legalVersion,
                'terms_ip' => $ip,
                'terms_user_agent' => $ua,
            ]);
            \App\Models\LegalConsent::create([
                'artist_id' => $artist->id,
                'type' => 'terms',
                'version' => $legalVersion,
                'granted' => true,
                'ip' => $ip,
                'user_agent' => $ua,
            ]);
            $artist->grantWelcomeTokens();
        } elseif (! $artist->google_id) {
            $artist->update([
                'google_id' => $googleUser->getId(),
                'email_verified_at' => now(),
            ]);
            // Backfill huella si aún no tenía trazabilidad
            if (! $artist->terms_accepted_at) {
                $artist->update([
                    'terms_accepted_at' => now(),
                    'terms_version' => $legalVersion,
                    'terms_ip' => $ip,
                    'terms_user_agent' => $ua,
                ]);
                \App\Models\LegalConsent::create([
                    'artist_id' => $artist->id,
                    'type' => 'terms',
                    'version' => $legalVersion,
                    'granted' => true,
                    'ip' => $ip,
                    'user_agent' => $ua,
                ]);
            }
        } elseif (! $artist->terms_accepted_at) {
            $artist->update([
                'terms_accepted_at' => now(),
                'terms_version' => $legalVersion,
                'terms_ip' => $ip,
                'terms_user_agent' => $ua,
            ]);
            \App\Models\LegalConsent::create([
                'artist_id' => $artist->id,
                'type' => 'terms',
                'version' => $legalVersion,
                'granted' => true,
                'ip' => $ip,
                'user_agent' => $ua,
            ]);
        }

        Auth::login($artist, true);

        return redirect()->intended(route('dashboard', absolute: false));
    }
}
