<?php

namespace App\Http\Controllers\AO;

use App\Http\Controllers\Controller;
use App\Models\Leave;

class DashboardController extends Controller
{
    public function index()
    {
        $pendingCount  = Leave::where('leave_status', 'Pending AO')->count();
        $approvedCount = Leave::where('ao_action', 'Approved')->count();
        $declinedCount = Leave::where('leave_status', 'Declined')
            ->whereNotNull('ao_id')->count();

        $recentLeaves = Leave::with('employee')
            ->whereIn('leave_status', ['Pending AO', 'Pending ASDS', 'Approved', 'Declined'])
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        return view('ao.dashboard', compact(
            'pendingCount',
            'approvedCount',
            'declinedCount',
            'recentLeaves'
        ));
    }
}
