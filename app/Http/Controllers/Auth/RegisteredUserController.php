<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Artist;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Honeypot: bots fill hidden fields, humans don't
        if ($request->filled('website_url')) {
            throw ValidationException::withMessages([
                'email' => __('Too many attempts. Please try again later.'),
            ]);
        }

        // Turnstile CAPTCHA verification
        $this->verifyTurnstile($request);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.Artist::class, new \App\Rules\DisposableEmailRule()],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'terms' => ['required', 'accepted'],
            'marketing' => ['nullable', 'boolean'],
        ]);

        $legalVersion = config('artid.legal_version');
        $ip = $request->ip();
        $ua = $request->userAgent();
        $marketing = $request->boolean('marketing');

        $artist = Artist::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'terms_accepted_at' => now(),
            'terms_version' => $legalVersion,
            'terms_ip' => $ip,
            'terms_user_agent' => $ua,
            'marketing_consent' => $marketing,
            'marketing_consent_at' => $marketing ? now() : null,
            'marketing_ip' => $marketing ? $ip : null,
        ]);

        \App\Models\LegalConsent::create([
            'artist_id' => $artist->id,
            'type' => 'terms',
            'version' => $legalVersion,
            'granted' => true,
            'ip' => $ip,
            'user_agent' => $ua,
        ]);
        if ($marketing) {
            \App\Models\LegalConsent::create([
                'artist_id' => $artist->id,
                'type' => 'marketing',
                'version' => $legalVersion,
                'granted' => true,
                'ip' => $ip,
                'user_agent' => $ua,
            ]);
        }

        $artist->grantWelcomeTokens();

        event(new Registered($artist));

        Auth::login($artist);

        return redirect(route('dashboard', absolute: false));
    }

    protected function verifyTurnstile(Request $request): void
    {
        $token = $request->input('cf-turnstile-response');
        $secretKey = config('services.turnstile.secret_key');

        if (!$secretKey) {
            // Turnstile not configured, skip verification
            return;
        }

        if (!$token) {
            throw ValidationException::withMessages([
                'email' => __('Please complete the security check.'),
            ]);
        }

        $response = Http::asForm()->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
            'secret' => $secretKey,
            'response' => $token,
            'remoteip' => $request->ip(),
        ]);

        if (!$response->successful() || !$response->json('success')) {
            throw ValidationException::withMessages([
                'email' => __('Security check failed. Please try again.'),
            ]);
        }
    }
}
