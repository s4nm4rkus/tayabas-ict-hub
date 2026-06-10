<?php

namespace App\Http\Controllers\ASDS;

use App\Http\Controllers\Controller;
use App\Models\Leave;

class DashboardController extends Controller
{
    public function index()
    {
        $pendingCount  = Leave::where('leave_status', 'Pending ASDS')->count();
        $approvedCount = Leave::where('asds_action', 'Approved')->count();
        $declinedCount = Leave::where('asds_action', 'Declined')->count();

        $recentLeaves = Leave::with('employee')
            ->whereIn('leave_status', ['Pending ASDS', 'Approved', 'Declined'])
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        return view('asds.dashboard', compact(
            'pendingCount',
            'approvedCount',
            'declinedCount',
            'recentLeaves'
        ));
    }
}
