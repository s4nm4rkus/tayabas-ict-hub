<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\AttendanceLog;
use App\Models\Employee;
use App\Models\Point;
use App\Services\FlexiAttendanceService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    public function __construct(protected FlexiAttendanceService $flexi)
    {
    }

    // ─────────────────────────────────────────────────────────────
    // GET: Employee list + optional selected employee detail
    // ─────────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $month      = $request->get('month', now()->format('Y-m'));
        $employeeId = $request->get('employee_id');

        [$year, $mon] = explode('-', $month);

        $monthQuery = Attendance::query()
            ->whereYear('t_date', $year)
            ->whereMonth('t_date', $mon);

        if ($request->filled('status') && $employeeId) {
            match ($request->status) {
                'late'      => $monthQuery->where('late_minutes', '>', 0),
                'undertime' => $monthQuery->where('undertime_minutes', '>', 0),
                'complete'  => $monthQuery->where('late_minutes', 0)->where('undertime_minutes', 0),
                default     => null,
            };
        }

        $allMonthRecords = (clone $monthQuery)->get();
        $byEmployee      = $allMonthRecords->groupBy('user_id');

        $employeesWithRecords = Employee::whereIn('id', $byEmployee->keys())
            ->orderBy('last_name')
            ->get();

        $selectedEmployee = null;
        $selectedRecords  = collect();

        if ($employeeId) {
            $selectedEmployee = Employee::with('user')->find($employeeId);

            if ($selectedEmployee) {
                $detailQuery = Attendance::where('user_id', $employeeId)
                    ->whereYear('t_date', $year)
                    ->whereMonth('t_date', $mon)
                    ->orderBy('t_date');

                if ($request->filled('status')) {
                    match ($request->status) {
                        'late'      => $detailQuery->where('late_minutes', '>', 0),
                        'undertime' => $detailQuery->where('undertime_minutes', '>', 0),
                        'complete'  => $detailQuery->where('late_minutes', 0)->where('undertime_minutes', 0),
                        default     => null,
                    };
                }

                $selectedRecords = $detailQuery->get();
            }
        }

        $employees = Employee::orderBy('last_name')->get();

        return view('hr.attendance.index', compact(
            'byEmployee',
            'employeesWithRecords',
            'employees',
            'selectedEmployee',
            'selectedRecords',
            'month',
        ));
    }

    // ─────────────────────────────────────────────────────────────
    // DELETE: Remove a single attendance record
    // ─────────────────────────────────────────────────────────────
    public function destroy(int $id)
    {
        $att        = Attendance::findOrFail($id);
        $employeeId = $att->user_id;
        $month      = Carbon::parse($att->t_date)->format('Y-m');
        $att->delete();

        return redirect()->route('hr.attendance.index', [
            'employee_id' => $employeeId,
            'month'       => $month,
        ])->with('success', 'Attendance record deleted.');
    }

    // ─────────────────────────────────────────────────────────────
    // DELETE: Reset ALL attendance data for a whole month
    // Removes: attendances + attendance_logs + points for that month
    // ─────────────────────────────────────────────────────────────
    public function resetMonth(Request $request)
    {
        $request->validate([
            'month' => 'required|date_format:Y-m',
        ]);

        [$year, $mon] = explode('-', $request->month);

        DB::transaction(function () use ($year, $mon) {
            // 1. Delete computed attendance rows
            $deleted = Attendance::whereYear('t_date', $year)
                ->whereMonth('t_date', $mon)
                ->delete();

            // 2. Delete raw punch logs for that month
            AttendanceLog::whereYear('punch_time', $year)
                ->whereMonth('punch_time', $mon)
                ->delete();

            // 3. Delete points earned that month
            Point::whereYear('t_date', $year)
                ->whereMonth('t_date', $mon)
                ->delete();
        });

        $monthLabel = Carbon::create($year, $mon, 1)->format('F Y');

        return redirect()
            ->route('hr.attendance.index', ['month' => $request->month])
            ->with('success', "All attendance data for {$monthLabel} has been cleared (attendance, logs & points).");
    }

    // ─────────────────────────────────────────────────────────────
    // DELETE: Reset ONE employee's attendance for a given month
    // Removes: their attendances + their raw logs + their points
    // ─────────────────────────────────────────────────────────────
    public function resetEmployee(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|integer',
            'month'       => 'required|date_format:Y-m',
        ]);

        $employee = Employee::findOrFail($request->employee_id);
        [$year, $mon] = explode('-', $request->month);

        DB::transaction(function () use ($employee, $year, $mon) {
            // 1. Delete attendance rows for this employee+month
            Attendance::where('user_id', $employee->id)
                ->whereYear('t_date', $year)
                ->whereMonth('t_date', $mon)
                ->delete();

            // 2. Delete raw punch logs for this employee+month
            AttendanceLog::where('emp_code', $employee->employee_no)
                ->whereYear('punch_time', $year)
                ->whereMonth('punch_time', $mon)
                ->delete();

            // 3. Delete points for this employee+month
            Point::where('userid', $employee->id)
                ->whereYear('t_date', $year)
                ->whereMonth('t_date', $mon)
                ->delete();
        });

        $monthLabel = Carbon::create($year, $mon, 1)->format('F Y');

        return redirect()
            ->route('hr.attendance.index', ['month' => $request->month])
            ->with('success', "{$employee->full_name}'s attendance for {$monthLabel} has been cleared.");
    }

    // ─────────────────────────────────────────────────────────────
    // GET: Export CSV
    // ─────────────────────────────────────────────────────────────
    public function exportCsv(Request $request)
    {
        $query = Attendance::orderBy('t_date', 'desc');

        if ($request->filled('employee_id')) {
            $query->where('user_id', $request->employee_id);
        }
        if ($request->filled('month')) {
            [$year, $mon] = explode('-', $request->month);
            $query->whereYear('t_date', $year)->whereMonth('t_date', $mon);
        }

        $records = $query->get();

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename=attendance_' . now()->format('Ymd') . '.csv',
        ];

        $callback = function () use ($records) {
            $file = fopen('php://output', 'w');
            fputcsv($file, [
                'Name', 'Position', 'Date',
                'AM Time In', 'AM Time Out',
                'PM Time In', 'PM Time Out',
                'Total Hours', 'Late (min)', 'Undertime (min)', 'Points Earned',
            ]);
            foreach ($records as $rec) {
                fputcsv($file, [
                    $rec->fullname,
                    $rec->position,
                    $rec->t_date,
                    $rec->am_time_in  ?? '',
                    $rec->am_time_out ?? '',
                    $rec->pm_time_in  ?? '',
                    $rec->pm_time_out ?? '',
                    $rec->total_hours,
                    $rec->late_minutes      ?? 0,
                    $rec->undertime_minutes ?? 0,
                    round((0.42 / 8) * (float) $rec->total_hours, 4),
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
