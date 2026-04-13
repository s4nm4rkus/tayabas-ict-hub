<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Leave;
use App\Models\CertRequest;
use App\Models\Attendance;
use App\Models\Board;

class DashboardController extends Controller
{
    public function index()
    {
        $totalEmployees     = Employee::count();
        $pendingLeaves      = Leave::where('leave_status', 'Pending HR')->count();
        $pendingCerts       = CertRequest::where('req_status', 'Pending HR')->count();
        $presentToday       = Attendance::whereDate('t_date', today())->count();
        $recentLeaves       = Leave::with('employee')
                                   ->orderBy('created_at', 'desc')
                                   ->take(5)->get();
        $recentAnnouncements = Board::with('user')
                                    ->orderBy('date_time', 'desc')
                                    ->take(3)->get();

        return view('admin.dashboard', compact(
            'totalEmployees', 'pendingLeaves',
            'pendingCerts', 'presentToday',
            'recentLeaves', 'recentAnnouncements'
        ));
    }
}