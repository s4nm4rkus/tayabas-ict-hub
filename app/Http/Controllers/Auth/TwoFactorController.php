<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TwoFactorController extends Controller
{
    public function show()
    {
        if (! session('pre_auth_user_id')) {
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
        $user   = User::findOrFail($userId);

        if ($user->otp !== $request->otp ||
            now()->gt($user->otp_expires_at)) {
            return back()->withErrors([
                'otp' => 'Invalid or expired OTP. Please try again.',
            ]);
        }

        $user->update([
            'otp'            => null,
            'otp_expires_at' => null,
        ]);

        session()->forget('pre_auth_user_id');
        session()->regenerate();
        Auth::login($user, true);

        if (! $user->pass_change) {
            return redirect()->route('password.change');
        }

        return $this->redirectByRole($user);
    }

    public function redirectByRole(User $user): \Illuminate\Http\RedirectResponse
    {
        // ── Step 1: Exact user_pos matches ────────────────────────────────
        // Covers all roles that have a unique user_pos value
        $exactMatch = match ($user->user_pos) {
            'Super Administrator'    => redirect()->route('admin.dashboard'),
            'HR'                     => redirect()->route('hr.dashboard'),
            'Administrative Officer' => redirect()->route('ao.dashboard'),
            'ASDS'                   => redirect()->route('asds.dashboard'),
            // ── Also cover the case where user_pos is literally stored
            // as 'Department Head' instead of a specific position name
            'Department Head'        => redirect()->route('head.dashboard'),
            default                  => null,
        };

        if ($exactMatch) {
            return $exactMatch;
        }

        // ── Step 2: role_type lookup from tbl_role ────────────────────────
        // Covers positions like:
        // 'Head Teacher'        → role_type = 'Department Head' → head
        // 'School Principal'    → role_type = 'Department Head' → head
        // 'Assistant Principal' → role_type = 'Department Head' → head
        // 'Master Teacher I'    → role_type = 'Department Head' → head
        // 'Teacher I/II/III'    → role_type = 'Employee' → employee
        // 'Administrative Aide' → role_type = 'Employee' → employee
        $roleType = Role::where('role_desc', $user->user_pos)->value('role_type');

        if ($roleType === 'Department Head') {
            return redirect()->route('head.dashboard');
        }

        // ── Step 3: Default → employee dashboard ─────────────────────────
        // Covers all Teaching and Non-Teaching employees
        return redirect()->route('employee.dashboard');
    }
}
