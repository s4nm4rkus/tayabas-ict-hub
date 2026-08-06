<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class LoginController extends Controller
{
    public function showLogin(Request $request)
    {
        if (Auth::check()) {
            return app(\App\Http\Controllers\Auth\TwoFactorController::class)
                ->redirectByRole(Auth::user());
        }

        /*
         * Store where the user intended to go BEFORE they were sent to login.
         * Laravel puts this in session automatically via the 'auth' middleware,
         * but when the user clicks a login button manually (e.g. from the ICT
         * navbar), there is no middleware redirect — so we capture the HTTP
         * Referer as a fallback intended URL.
         *
         * Priority:
         *  1. session()->previousUrl()  — set by Laravel's auth middleware
         *  2. HTTP Referer header        — set when user clicks a login link
         *  3. Nothing (session already has it from a prior middleware redirect)
         */
        $referer = $request->headers->get('referer');

        if ($referer && ! session()->has('url.intended')) {
            // Only store it if it's from our own domain and not the login page itself
            $loginUrl = route('login');
            $appUrl   = config('app.url');

            if (
                str_starts_with($referer, $appUrl) &&
                ! str_starts_with($referer, $loginUrl)
            ) {
                session()->put('url.intended', $referer);
            }
        }

        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            'username' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('username', $request->username)
            ->where('user_stat', 'Enabled')
            ->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'username' => 'Invalid username or password.',
            ])->withInput(['username' => $request->username]);
        }

        // Store user temporarily before OTP verification
        session(['pre_auth_user_id' => $user->id]);

        // Generate OTP
        $otp = rand(100000, 999999);
        $user->update([
            'otp'            => $otp,
            'otp_expires_at' => now()->addMinutes(10),
        ]);

        // Send OTP via email
        Mail::to($user->username)->send(new OtpMail($otp));

        return redirect()->route('2fa.show');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
