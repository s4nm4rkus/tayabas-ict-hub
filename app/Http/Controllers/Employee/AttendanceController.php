<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\AttendanceLog;
use App\Models\Employee;
use App\Models\Point;
use App\Services\FlexiAttendanceService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function __construct(protected FlexiAttendanceService $flexi)
    {
    }

    // ─────────────────────────────────────────────────────────────
    // GET: My attendance list — filterable by month
    // ─────────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        // Resolve employee via employee_no linked to the authenticated user
        $employee = Employee::where('user_id', Auth::id())->firstOrFail();

        $month = $request->get('month', now()->format('Y-m'));
        [$year, $mon] = explode('-', $month);

        // Monthly attendance records, newest first
        $attendance = Attendance::where('user_id', $employee->id)
            ->whereYear('t_date', $year)
            ->whereMonth('t_date', $mon)
            ->orderBy('t_date', 'asc')
            ->get();

        // Monthly summary totals
        $totalHours     = $attendance->sum('total_hours');
        $totalLate      = $attendance->sum('late_minutes');
        $totalUndertime = $attendance->sum('undertime_minutes');
        $totalPoints    = Point::where('userid', $employee->id)
            ->whereYear('t_date', $year)
            ->whereMonth('t_date', $mon)
            ->sum('acc_points');

        // All-time accumulated points for the summary card
        $allTimePoints = Point::where('userid', $employee->id)->sum('acc_points');

        return view('employee.attendance.index', compact(
            'employee',
            'attendance',
            'month',
            'totalHours',
            'totalLate',
            'totalUndertime',
            'totalPoints',
            'allTimePoints',
        ));
    }

    // ─────────────────────────────────────────────────────────────
    // GET: Print my DTR — same logic as HR's printDtr but
    //      scoped to the authenticated employee
    // ─────────────────────────────────────────────────────────────
    public function printDtr(Request $request)
    {
        $request->validate([
            'month' => 'required|date_format:Y-m',
        ]);

        $employee = Employee::where('user_id', Auth::id())->firstOrFail();
        [$year, $mon] = explode('-', $request->month);

        $daysInMonth = Carbon::create($year, $mon, 1)->daysInMonth;
        $monthLabel  = Carbon::create($year, $mon, 1)->format('F Y');

        // Fetch attendance for this employee+month, keyed by date string
        $attendanceMap = Attendance::where('user_id', $employee->id)
            ->whereYear('t_date', $year)
            ->whereMonth('t_date', $mon)
            ->get()
            ->keyBy(fn ($a) => Carbon::parse($a->t_date)->format('Y-m-d'));

        // Build full day-by-day array for the blade
        $days = [];
        for ($d = 1; $d <= $daysInMonth; $d++) {
            $dateObj = Carbon::create($year, $mon, $d);
            $dateKey = $dateObj->format('Y-m-d');
            $att     = $attendanceMap->get($dateKey);

            $isWeekend = $dateObj->isWeekend();
            $isHoliday = false; // plug in holiday logic here if needed
            $absent    = ! $att && ! $isWeekend && ! $isHoliday;

            $amIn  = null;
            $amOut = null;
            $pmIn  = null;
            $pmOut = null;

            if ($att) {
                $amIn  = $att->am_time_in ?: null;
                $amOut = $att->am_time_out ?: null;
                $pmIn  = $att->pm_time_in ?: null;
                $pmOut = $att->pm_time_out ?: null;

                // Fallback: stale row with no times — recompute from raw logs
                if (! $amIn && ! $pmOut) {
                    $rawPunches = AttendanceLog::where('emp_code', $employee->employee_no)
                        ->whereDate('punch_time', $dateKey)
                        ->orderBy('punch_time')
                        ->pluck('punch_time')
                        ->map(fn ($t) => Carbon::parse($t))
                        ->values()
                        ->all();

                    if (count($rawPunches) >= 2) {
                        $recomputed = $this->flexi->computeDay(
                            $dateKey,
                            $rawPunches[0],
                            end($rawPunches),
                            $rawPunches
                        );
                        $amIn  = $recomputed['am_time_in'];
                        $amOut = $recomputed['am_time_out'];
                        $pmIn  = $recomputed['pm_time_in'];
                        $pmOut = $recomputed['pm_time_out'];

                        // Patch DB row silently so next view is instant
                        $att->update([
                            'am_time_in'        => $recomputed['am_time_in'],
                            'am_time_out'       => $recomputed['am_time_out'],
                            'pm_time_in'        => $recomputed['pm_time_in'],
                            'pm_time_out'       => $recomputed['pm_time_out'],
                            'total_hours'       => $recomputed['total_hours'],
                            'late_minutes'      => $recomputed['late_minutes'],
                            'undertime_minutes' => $recomputed['undertime_minutes'],
                        ]);

                        $att->total_hours       = $recomputed['total_hours'];
                        $att->late_minutes      = $recomputed['late_minutes'];
                        $att->undertime_minutes = $recomputed['undertime_minutes'];
                    }
                }
            }

            $days[] = [
                'day'          => $d,
                'date'         => $dateKey,
                'is_weekend'   => $isWeekend,
                'is_holiday'   => $isHoliday,
                'holiday_name' => null,
                'absent'       => $absent,
                'am_time_in'   => $amIn,
                'am_time_out'  => $amOut,
                'pm_time_in'   => $pmIn,
                'pm_time_out'  => $pmOut,
                'undertime'    => (int)   ($att?->undertime_minutes ?? 0),
                'late'         => (int)   ($att?->late_minutes      ?? 0),
                'total_hours'  => (float) ($att?->total_hours       ?? 0),
            ];
        }

        $dtr = [
            'employee'        => $employee,
            'month'           => $monthLabel,
            'year'            => $year,
            'days'            => $days,
            'total_late'      => $attendanceMap->sum('late_minutes'),
            'total_undertime' => $attendanceMap->sum('undertime_minutes'),
            'total_hours'     => $attendanceMap->sum('total_hours'),
        ];

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('hr.attendance.dtr.pdf', compact('dtr'))
            ->setPaper('letter', 'portrait');

        $safeName = str_replace([',', ' '], ['', '_'], $employee->full_name);

        return $pdf->stream("DTR_{$safeName}_{$request->month}.pdf");
    }
}
