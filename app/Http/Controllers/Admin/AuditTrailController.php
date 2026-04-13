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

        if ($request->filled('search')) {
            $query->where('action_done', 'like', '%'.$request->search.'%');
        }

        if ($request->filled('date')) {
            $query->whereDate('action_at', $request->date);
        }

        $logs = $query->paginate(30);
        return view('admin.audit.index', compact('logs'));
    }
}