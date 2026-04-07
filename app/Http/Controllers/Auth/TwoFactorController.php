<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TwoFactorController extends Controller
{
    public function show()
    {
        if (!session('pre_auth_user_id')) {
            return redirect()->route('login');
        }
        return view('auth.2fa');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $userId = session('pre_auth_user_id');
        $user = User::findOrFail($userId);

        // Check OTP validity
        if ($user->otp !== $request->otp ||
            now()->gt($user->otp_expires_at)) {
            return back()->withErrors([
                'otp' => 'Invalid or expired OTP. Please try again.',
            ]);
        }

        // Clear OTP and log in
        $user->update([
            'otp' => null,
            'otp_expires_at' => null,
        ]);

        Auth::login($user);
        session()->forget('pre_auth_user_id');

        // Force password change check
        if (!$user->pass_change) {
            return redirect()->route('password.change');
        }

        return $this->redirectByRole($user);
    }

    public function redirectByRole(User $user)
{
    return match($user->user_pos) {
        'Super Administrator' => redirect()->route('admin.dashboard'),
        'HR'                  => redirect()->route('hr.dashboard'),
        default               => $this->redirectByRoleType($user),
    };
}

public function redirectByRoleType(User $user)
{
    $role = \App\Models\Role::where('role_desc', $user->user_pos)->first();

    if ($role && $role->role_type === 'Department Head') {
        return redirect()->route('head.dashboard');
    }

    return redirect()->route('employee.dashboard');
}
}