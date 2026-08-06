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

        /*
         * Determine final redirect destination.
         *
         * Logic:
         *  1. If session has 'url.intended', check whether it belongs to
         *     a known section (ICT admin, HR, etc.) that this user can access.
         *  2. If the intended URL is accessible for this user → go there.
         *  3. Otherwise fall back to the role-based default dashboard.
         */
        $intended     = session()->pull('url.intended');
        $defaultRoute = $this->redirectByRole($user)->getTargetUrl();

        if ($intended && $this->userCanVisit($user, $intended)) {
            return redirect($intended);
        }

        return redirect($defaultRoute);
    }

    /**
     * Decide whether the logged-in user is allowed to visit the
     * intended URL. This prevents a Personnel user who somehow ends
     * up on the ICT login button from being sent to the ICT dashboard.
     *
     * Rules:
     *  - ICT admin routes  → only Super Administrator
     *  - HR routes         → only HR
     *  - Admin routes      → only Super Administrator
     *  - AO routes         → only Administrative Officer
     *  - ASDS routes       → only ASDS
     *  - Head routes       → only Department Head (role_type)
     *  - Anything else     → allow (public ICT pages, etc.)
     */
    private function userCanVisit(User $user, string $url): bool
    {
        $appUrl = rtrim(config('app.url'), '/');
        $path   = str_replace($appUrl, '', $url); // strip domain → /ict/admin/...

        $pos = $user->user_pos;

        // Map URL path prefixes to the role that owns them
        $rules = [
            '/ict/admin'   => 'Super Administrator',
            '/admin'       => 'Super Administrator',
            '/hr'          => 'HR',
            '/ao'          => 'Administrative Officer',
            '/asds'        => 'ASDS',
            '/head'        => 'Department Head',  // role_type, checked below
            '/employee'    => null,               // null = any authenticated user
        ];

        foreach ($rules as $prefix => $requiredPos) {
            if (str_starts_with($path, $prefix)) {
                if ($requiredPos === null) {
                    return true; // any authenticated user
                }

                // Special case: Department Head is stored in role_type, not user_pos
                if ($requiredPos === 'Department Head') {
                    $roleType = \App\Models\Role::where('role_desc', $pos)->value('role_type');
                    return $pos === 'Department Head' || $roleType === 'Department Head';
                }

                return $pos === $requiredPos;
            }
        }

        // No restricted prefix matched → allow (e.g. public ICT pages)
        return true;
    }

    public function redirectByRole(User $user): \Illuminate\Http\RedirectResponse
    {
        // ── Step 1: Exact user_pos matches ────────────────────────────────
        $exactMatch = match ($user->user_pos) {
            'Super Administrator'    => redirect()->route('admin.dashboard'),
            'HR'                     => redirect()->route('hr.dashboard'),
            'Administrative Officer' => redirect()->route('ao.dashboard'),
            'ASDS'                   => redirect()->route('asds.dashboard'),
            'Department Head'        => redirect()->route('head.dashboard'),
            default                  => null,
        };

        if ($exactMatch) {
            return $exactMatch;
        }

        // ── Step 2: role_type lookup from tbl_role ────────────────────────
        $roleType = Role::where('role_desc', $user->user_pos)->value('role_type');

        if ($roleType === 'Department Head') {
            return redirect()->route('head.dashboard');
        }

        // ── Step 3: Default → employee dashboard ─────────────────────────
        return redirect()->route('employee.dashboard');
    }

    public function dashboard(): \Illuminate\Http\RedirectResponse
    {
        $user = User::findOrFail(Auth::id());
        return $this->redirectByRole($user);
    }
}
