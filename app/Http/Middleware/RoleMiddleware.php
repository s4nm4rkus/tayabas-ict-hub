<?php

namespace App\Http\Middleware;

use App\Models\Role;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): mixed
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $userPos = Auth::user()->user_pos;

        // ── Direct match: user_pos exactly matches one of the allowed roles ──
        // e.g. 'HR', 'ASDS', 'Administrative Officer', 'Super Administrator'
        if (in_array($userPos, $roles)) {
            return $next($request);
        }

        // ── Type match: check role_type from tbl_role ──────────────────────
        // e.g. user_pos = 'Head Teacher' has role_type = 'Department Head'
        // So middleware('role:Department Head') also allows Head Teacher,
        // School Principal, Assistant Principal, Master Teacher, etc.
        $roleType = Role::where('role_desc', $userPos)
            ->value('role_type');

        if ($roleType && in_array($roleType, $roles)) {
            return $next($request);
        }

        abort(403, 'Unauthorized.');
    }
}
