<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\AttendanceLog;
use App\Models\Employee;
use App\Models\Point;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class AttendanceController extends Controller
{
    // ─────────────────────────────────────────────────────────────
    // GET: Employee list + optional selected employee detail
    // (same logic as HR\AttendanceController@index)
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

        return view('admin.attendance.index', compact(
            'byEmployee',
            'employeesWithRecords',
            'employees',
            'selectedEmployee',
            'selectedRecords',
            'month',
        ));
    }

    // ─────────────────────────────────────────────────────────────
    // POST: Manual attendance entry (Admin-only feature, kept as-is)
    // ─────────────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'user_id'     => 'required|integer',
            't_date'      => 'required|date',
            'am_time_in'  => 'nullable',
            'am_time_out' => 'nullable',
            'pm_time_in'  => 'nullable',
            'pm_time_out' => 'nullable',
        ]);

        $employee = Employee::findOrFail($request->user_id);

        $totalHours = $this->calculateTotalHours(
            $request->am_time_in,
            $request->am_time_out,
            $request->pm_time_in,
            $request->pm_time_out
        );

        Attendance::updateOrCreate(
            [
                'user_id' => $employee->id,
                't_date'  => $request->t_date,
            ],
            [
                'fullname'    => $employee->full_name,
                'position'    => $employee->user?->user_pos,
                'am_time_in'  => $request->am_time_in,
                'am_time_out' => $request->am_time_out,
                'pm_time_in'  => $request->pm_time_in,
                'pm_time_out' => $request->pm_time_out,
                'total_hours' => $totalHours,
            ]
        );

        Point::updateOrCreate(
            [
                'userid' => $employee->id,
                't_date' => $request->t_date,
            ],
            ['acc_points' => round((0.42 / 8) * $totalHours, 4)]
        );

        // Land on the employee + month that was just edited so the result is visible
        return redirect()->route('admin.attendance.index', [
            'employee_id' => $employee->id,
            'month'       => Carbon::parse($request->t_date)->format('Y-m'),
        ])->with('success', 'Attendance recorded.');
    }

    public function dtr(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|integer',
            'month'       => 'required|date_format:Y-m',
        ]);

        $employee = Employee::with('user')->findOrFail($request->employee_id);
        $start    = Carbon::parse($request->month . '-01');

        $records = Attendance::where('user_id', $employee->id)
            ->whereYear('t_date', $start->year)
            ->whereMonth('t_date', $start->month)
            ->get()
            ->keyBy(fn ($r) => Carbon::parse($r->t_date)->day);

        // Optional: ['2026-12-25' => 'Christmas Day', ...] — pull from your holidays table if you have one
        $holidays = [];

        $days = [];
        for ($d = 1; $d <= $start->daysInMonth; $d++) {
            $date    = $start->copy()->day($d);
            $rec     = $records[$d] ?? null;
            $holiday = $holidays[$date->toDateString()] ?? null;

            $days[] = [
                'day'          => $d,
                'date'         => $date->toDateString(),
                'is_weekend'   => $date->isWeekend(),
                'is_holiday'   => (bool) $holiday,
                'holiday_name' => $holiday,
                'absent'       => !$rec,
                'am_time_in'   => $rec?->am_time_in,
                'am_time_out'  => $rec?->am_time_out,
                'pm_time_in'   => $rec?->pm_time_in,
                'pm_time_out'  => $rec?->pm_time_out,
                'undertime'    => (int) ($rec?->undertime_minutes ?? 0),
            ];
        }

        $dtr = [
            'employee'        => $employee,
            'month'           => $start->format('F Y'),
            'days'            => $days,
            'total_undertime' => (int) $records->sum('undertime_minutes'),
            'total_late'      => (int) $records->sum('late_minutes'),
            'total_hours'     => (float) $records->sum('total_hours'),
        ];

        return Pdf::loadView('admin.attendance.dtr.pdf', compact('dtr'))
        ->setPaper('a4', 'portrait')
        ->stream("DTR_{$employee->last_name}_{$start->format('Y-m')}.pdf");
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

        return redirect()->route('admin.attendance.index', [
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
            Attendance::whereYear('t_date', $year)
                ->whereMonth('t_date', $mon)
                ->delete();

            AttendanceLog::whereYear('punch_time', $year)
                ->whereMonth('punch_time', $mon)
                ->delete();

            Point::whereYear('t_date', $year)
                ->whereMonth('t_date', $mon)
                ->delete();
        });

        $monthLabel = Carbon::create($year, $mon, 1)->format('F Y');

        return redirect()
            ->route('admin.attendance.index', ['month' => $request->month])
            ->with('success', "All attendance data for {$monthLabel} has been cleared (attendance, logs & points).");
    }

    // ─────────────────────────────────────────────────────────────
    // DELETE: Reset ONE employee's attendance for a given month
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
            Attendance::where('user_id', $employee->id)
                ->whereYear('t_date', $year)
                ->whereMonth('t_date', $mon)
                ->delete();

            AttendanceLog::where('emp_code', $employee->employee_no)
                ->whereYear('punch_time', $year)
                ->whereMonth('punch_time', $mon)
                ->delete();

            Point::where('userid', $employee->id)
                ->whereYear('t_date', $year)
                ->whereMonth('t_date', $mon)
                ->delete();
        });

        $monthLabel = Carbon::create($year, $mon, 1)->format('F Y');

        return redirect()
            ->route('admin.attendance.index', ['month' => $request->month])
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

    private function calculateTotalHours($amIn, $amOut, $pmIn, $pmOut): float
    {
        $total = 0;

        if ($amIn && $amOut) {
            $total += Carbon::parse($amOut)->diffInMinutes(Carbon::parse($amIn)) / 60;
        }

        if ($pmIn && $pmOut) {
            $total += Carbon::parse($pmOut)->diffInMinutes(Carbon::parse($pmIn)) / 60;
        }

        return round($total, 2);
    }


}
