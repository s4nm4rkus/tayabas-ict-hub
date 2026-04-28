<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ForcePasswordChange
{
    public function handle(Request $request, Closure $next): mixed
    {
        if (! Auth::check()) {
            return $next($request);
        }

        // Skip these routes entirely
        if ($request->routeIs(
            'password.change',
            'password.update',
            'logout',
            '2fa.show',
            '2fa.verify',
            'login',
            'login.post'
        )) {
            return $next($request);
        }

        // Don't interrupt POST requests — let CSRF verify first
        if ($request->isMethod('POST') ||
            $request->isMethod('PUT') ||
            $request->isMethod('PATCH') ||
            $request->isMethod('DELETE')) {
            return $next($request);
        }

        if (! Auth::user()->pass_change) {
            if ($request->routeIs('password.change') || $request->routeIs('password.update')) {
                return $next($request);
            }

            return redirect()->route('password.change');
        }

        return $next($request);
    }
}
