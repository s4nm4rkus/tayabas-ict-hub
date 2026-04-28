<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Point;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Attendance::with('employee')
            ->orderBy('t_date', 'desc');

        if ($request->filled('date')) {
            $query->whereDate('t_date', $request->date);
        }

        if ($request->filled('employee_id')) {
            $query->where('user_id', $request->employee_id);
        }

        $attendance = $query->paginate(30);
        $employees = Employee::orderBy('last_name')->get();

        return view('admin.attendance.index', compact('attendance', 'employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            't_date' => 'required|date',
            'am_time_in' => 'nullable',
            'am_time_out' => 'nullable',
            'pm_time_in' => 'nullable',
            'pm_time_out' => 'nullable',
        ]);

        $employee = Employee::findOrFail($request->user_id);

        // Calculate total hours
        $totalHours = $this->calculateTotalHours(
            $request->am_time_in,
            $request->am_time_out,
            $request->pm_time_in,
            $request->pm_time_out
        );

        Attendance::updateOrCreate(
            [
                'user_id' => $employee->id,
                't_date' => $request->t_date,
            ],
            [
                'fullname' => $employee->full_name,
                'position' => $employee->user?->user_pos,
                'am_time_in' => $request->am_time_in,
                'am_time_out' => $request->am_time_out,
                'pm_time_in' => $request->pm_time_in,
                'pm_time_out' => $request->pm_time_out,
                'total_hours' => $totalHours,
            ]
        );

        // Auto-calculate leave points
        $accPoints = (0.42 / 8) * $totalHours;
        Point::updateOrCreate(
            [
                'userid' => $employee->id,
                't_date' => $request->t_date,
            ],
            ['acc_points' => round($accPoints, 4)]
        );

        return redirect()->route('admin.attendance.index')
            ->with('success', 'Attendance recorded.');
    }

    public function destroy(int $id)
    {
        Attendance::findOrFail($id)->delete();

        return redirect()->route('admin.attendance.index')
            ->with('success', 'Attendance record deleted.');
    }

    private function calculateTotalHours($amIn, $amOut, $pmIn, $pmOut): float
    {
        $total = 0;

        if ($amIn && $amOut) {
            $in = Carbon::parse($amIn);
            $out = Carbon::parse($amOut);
            $total += $out->diffInMinutes($in) / 60;
        }

        if ($pmIn && $pmOut) {
            $in = Carbon::parse($pmIn);
            $out = Carbon::parse($pmOut);
            $total += $out->diffInMinutes($in) / 60;
        }

        return round($total, 2);
    }
}
