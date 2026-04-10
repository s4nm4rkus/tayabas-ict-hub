<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserStatus
{
    public function handle(Request $request, Closure $next): mixed
    {
        if (Auth::check() && Auth::user()->user_stat === 'Disabled') {
            Auth::logout();
            return redirect()->route('login')->withErrors([
                'username' => 'Your account has been disabled. Contact the administrator.',
            ]);
        }

        return $next($request);
    }
}