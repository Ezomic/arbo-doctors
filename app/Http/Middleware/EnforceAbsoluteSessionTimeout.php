<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnforceAbsoluteSessionTimeout
{
    private const MAX_SESSION_SECONDS = 8 * 60 * 60;

    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $startedAt = $request->session()->get('_session_started_at');

            if ($startedAt === null) {
                $request->session()->put('_session_started_at', now()->timestamp);
            } elseif (now()->timestamp - $startedAt > self::MAX_SESSION_SECONDS) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')->with('status', 'Your session has expired. Please log in again.');
            }
        }

        return $next($request);
    }
}
