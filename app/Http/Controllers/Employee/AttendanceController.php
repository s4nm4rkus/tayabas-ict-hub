<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Point;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $employee = Employee::where('user_id', Auth::id())->firstOrFail();

        $attendance = Attendance::where('user_id', $employee->id)
            ->orderBy('t_date', 'desc')
            ->paginate(20);

        $totalPoints = Point::where('userid', $employee->id)
            ->sum('acc_points');

        return view('employee.attendance.index', compact(
            'attendance', 'totalPoints'
        ));
    }
}
