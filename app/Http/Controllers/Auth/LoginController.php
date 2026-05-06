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
    public function showLogin()
    {
        // If already authenticated, log them out first before showing login.
        // This fixes the issue where being logged in on one tab/role
        // blocks the login page from loading in the same browser.
        if (Auth::check()) {
            Auth::logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();
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
