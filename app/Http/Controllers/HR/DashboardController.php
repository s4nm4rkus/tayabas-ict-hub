<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Leave;
use App\Models\CertRequest;
use App\Models\Board;

class DashboardController extends Controller
{
    public function index()
    {
        $pendingLeaves = Leave::where('leave_status', 'Pending HR')->count();
        $pendingCerts  = CertRequest::where('req_status', 'Pending HR')->count();
        $totalEmployees = Employee::count();
        $approvedToday = Leave::where('leave_status', 'Approved')
                              ->whereDate('updated_at', today())->count();
        $recentLeaves  = Leave::with('employee')
                              ->where('leave_status', 'Pending HR')
                              ->orderBy('created_at', 'desc')
                              ->take(5)->get();
        $announcements = Board::with('user')
                              ->orderBy('date_time', 'desc')
                              ->take(3)->get();

        return view('hr.dashboard', compact(
            'pendingLeaves', 'pendingCerts',
            'totalEmployees', 'approvedToday',
            'recentLeaves', 'announcements'
        ));
    }
}