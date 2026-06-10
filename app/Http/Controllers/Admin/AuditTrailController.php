<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditTrail;
use Illuminate\Http\Request;

class AuditTrailController extends Controller
{
    public function index(Request $request)
    {
        $query = AuditTrail::with('user')
            ->orderBy('action_at', 'desc');

        // ── Search by action OR username ───────────────────────────────────
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('action_done', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function ($u) use ($search) {
                      $u->where('username', 'like', '%' . $search . '%')
                        ->orWhere('user_pos', 'like', '%' . $search . '%');
                  });
            });
        }

        // ── Filter by date ─────────────────────────────────────────────────
        if ($request->filled('date')) {
            $query->whereDate('action_at', $request->date);
        }

        $logs = $query->paginate(30)->withQueryString();

        return view('admin.audit.index', compact('logs'));
    }
}
