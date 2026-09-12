<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $supported = ['es', 'en'];

        $locale = $request->cookie('locale');

        if (! $locale) {
            $browser = $request->getPreferredLanguage($supported);
            $locale = $browser ? strtolower($browser) : 'es';
        }

        if (! in_array($locale, $supported)) {
            $locale = 'es';
        }

        App::setLocale($locale);

        return $next($request);
    }
}
