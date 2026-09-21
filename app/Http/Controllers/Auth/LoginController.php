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
    /**
     * Public routes that should NEVER be stored as url.intended.
     * These are pages anyone can visit — landing on them after login
     * is meaningless; the user should go to their role dashboard instead.
     */
    private array $publicPrefixes = [
        '/units/',     // /units/personnel, /units/ict, etc.
        '/ict/forms',  // public ICT request forms page
        '/login',      // login page itself
        '/2fa',        // OTP page
    ];

    public function showLogin(Request $request)
    {
        if (Auth::check()) {
            return app(\App\Http\Controllers\Auth\TwoFactorController::class)
                ->redirectByRole(Auth::user());
        }

        $referer = $request->headers->get('referer');

        if ($referer && ! session()->has('url.intended')) {
            $appUrl  = rtrim(config('app.url'), '/');
            $loginUrl = route('login');

            // Must be from our own domain
            if (str_starts_with($referer, $appUrl)) {
                $path = str_replace($appUrl, '', $referer);

                // Only store if it is NOT a public/guest-only page
                $isPublic = collect($this->publicPrefixes)
                    ->contains(fn ($prefix) => str_starts_with($path, $prefix));

                if (! $isPublic) {
                    session()->put('url.intended', $referer);
                }
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

        session(['pre_auth_user_id' => $user->id]);

        $otp = rand(100000, 999999);
        $user->update([
            'otp'            => $otp,
            'otp_expires_at' => now()->addMinutes(10),
        ]);

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
