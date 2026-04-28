<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Board;
use App\Models\CertRequest;
use App\Models\Employee;
use App\Models\Leave;
use App\Models\Point;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $employee = Employee::where('user_id', Auth::id())->first();

        $pendingLeaves = 0;
        $approvedLeaves = 0;
        $totalPoints = 0;
        $pendingCerts = 0;

        if ($employee) {
            $pendingLeaves = Leave::where('user_id', $employee->id)
                ->whereIn('leave_status', ['Pending HR', 'Pending Head'])
                ->count();
            $approvedLeaves = Leave::where('user_id', $employee->id)
                ->where('leave_status', 'Approved')
                ->count();
            $totalPoints = Point::where('userid', $employee->id)
                ->sum('acc_points');
            $pendingCerts = CertRequest::where('user_id', $employee->id)
                ->where('req_status', 'Pending HR')
                ->count();
        }

        $announcements = Board::with('user')
            ->orderBy('date_time', 'desc')
            ->take(5)->get();

        return view('employee.dashboard', compact(
            'employee', 'pendingLeaves', 'approvedLeaves',
            'totalPoints', 'pendingCerts', 'announcements'
        ));
    }
}
