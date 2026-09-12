<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TrackLastLogin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            $shouldUpdate = ! $user->last_login_at
                || $user->last_login_at->diffInMinutes(now()) >= 1;

            if ($shouldUpdate) {
                $user->update(['last_login_at' => now()]);
            }
        }

        return $next($request);
    }
}
