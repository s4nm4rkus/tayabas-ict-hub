<?php

namespace App\Http\Controllers\ICT;

use App\Http\Controllers\Controller;
use App\Models\IctTicket;
use App\Models\IctEmailRequest;
use App\Models\IctDtsRequest;
use App\Models\IctHelpdeskRequest;

class IctDashboardController extends Controller
{
    /**
     * GET /ict/admin/dashboard
     * Central ICT admin overview — combines TA tickets + Email requests.
     */
    public function index()
    {
        // ── TA ticket counts ──────────────────────────────────────────────
        $taCounts = [
            'all'         => IctTicket::count(),
            'pending'     => IctTicket::where('status', 'Pending')->count(),
            'in_progress' => IctTicket::where('status', 'In Progress')->count(),
            'resolved'    => IctTicket::where('status', 'Resolved')->count(),
            'closed'      => IctTicket::where('status', 'Closed')->count(),
            'cancelled'   => IctTicket::where('status', 'Cancelled')->count(),
        ];

        // ── Email request counts ──────────────────────────────────────────
        $emailCounts = [
            'all'         => IctEmailRequest::count(),
            'pending'     => IctEmailRequest::where('status', 'Pending')->count(),
            'in_progress' => IctEmailRequest::where('status', 'In Progress')->count(),
            'resolved'    => IctEmailRequest::where('status', 'Resolved')->count(),
            'closed'      => IctEmailRequest::where('status', 'Closed')->count(),
            'cancelled'   => IctEmailRequest::where('status', 'Cancelled')->count(),
        ];

        $dtsCounts = [
            'all'         => IctDtsRequest::count(),
            'pending'     => IctDtsRequest::where('status', 'Pending')->count(),
            'in_progress' => IctDtsRequest::where('status', 'In Progress')->count(),
            'resolved'    => IctDtsRequest::where('status', 'Resolved')->count(),
            'closed'      => IctDtsRequest::where('status', 'Closed')->count(),
            'cancelled'   => IctDtsRequest::where('status', 'Cancelled')->count(),
        ];

        $helpdeskCounts = [
            'all'         => IctHelpdeskRequest::count(),
            'pending'     => IctHelpdeskRequest::where('status', 'Pending')->count(),
            'in_progress' => IctHelpdeskRequest::where('status', 'In Progress')->count(),
            'resolved'    => IctHelpdeskRequest::where('status', 'Resolved')->count(),
            'closed'      => IctHelpdeskRequest::where('status', 'Closed')->count(),
            'cancelled'   => IctHelpdeskRequest::where('status', 'Cancelled')->count(),
        ];

        // ── Combined pending (used by topbar badge + alert banner) ────────
        $taPending    = $taCounts['pending'];
        $emailPending = $emailCounts['pending'];
        $dtsPending   = $dtsCounts['pending'];
        $helpdeskPending = $helpdeskCounts['pending'];
        $totalPending = $taPending + $emailPending + $dtsPending + $helpdeskPending;

        // ── Latest 6 of each ──────────────────────────────────────────────
        $recentTa = IctTicket::orderByDesc('created_at')->limit(6)->get();
        $recentEmail = IctEmailRequest::orderByDesc('created_at')->limit(6)->get();
        $recentDts = IctDtsRequest::orderByDesc('created_at')->limit(6)->get();
        $recentHelpdesk = IctHelpdeskRequest::orderByDesc('created_at')->limit(6)->get();
        return view('ict.admin.dashboard', compact(
            'taCounts',
            'emailCounts',
            'dtsCounts',
            'taPending',
            'emailPending',
            'dtsPending',
            'totalPending',
            'recentDts',
            'recentTa',
            'recentEmail',
            'recentHelpdesk',
            'helpdeskPending',
            'helpdeskCounts',
        ));
    }
}
