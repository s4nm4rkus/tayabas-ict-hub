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

class ZktecoImportController extends Controller
{
    public function __construct(protected FlexiAttendanceService $flexi)
    {
    }

    // ─────────────────────────────────────────────────────────────
    // GET: Show the upload form
    // ─────────────────────────────────────────────────────────────
    public function showForm()
    {
        return view('hr.zkteco.upload');
    }

    // ─────────────────────────────────────────────────────────────
    // POST: Parse .txt, save raw logs, save computed attendance
    // ─────────────────────────────────────────────────────────────
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:txt,dat|max:20480',
        ]);

        $file     = $request->file('file');
        $filename = $file->getClientOriginalName();
        $content  = file_get_contents($file->getRealPath());

        $rows = $this->flexi->parseTxtFile($content);

        if (empty($rows)) {
            return back()->withErrors(['file' => 'No valid attendance records found in the file.']);
        }

        // Save raw logs (skip exact duplicates)
        $rawSaved = 0;
        foreach ($rows as $row) {
            $exists = AttendanceLog::where('emp_code', $row['emp_code'])
                ->where('punch_time', $row['punch_time']->format('Y-m-d H:i:s'))
                ->exists();

            if (! $exists) {
                AttendanceLog::create([
                    'emp_code'    => $row['emp_code'],
                    'punch_time'  => $row['punch_time'],
                    'punch_state' => $row['punch_state'],
                    'verify_type' => $row['verify_type'],
                    'source_file' => $filename,
                ]);
                $rawSaved++;
            }
        }

        // Group by employee+day and compute
        $grouped  = $this->flexi->groupByEmployeeDay($rows);
        $imported = 0;
        $skipped  = [];

        DB::transaction(function () use ($grouped, $filename, &$imported, &$skipped) {
            foreach ($grouped as $empCode => $days) {
                $employee = Employee::where('employee_no', $empCode)->first();

                if (! $employee) {
                    $skipped[] = "Employee #{$empCode} not found — skipped.";
                    continue;
                }

                foreach ($days as $date => $computed) {
                    Attendance::updateOrCreate(
                        ['user_id' => $employee->id, 't_date' => $date],
                        [
                            'fullname'          => $employee->full_name,
                            'position'          => $employee->user?->user_pos,
                            'am_time_in'        => $computed['am_time_in'],
                            'am_time_out'       => $computed['am_time_out'],
                            'pm_time_in'        => $computed['pm_time_in'],
                            'pm_time_out'       => $computed['pm_time_out'],
                            'total_hours'       => $computed['total_hours'],
                            'late_minutes'      => $computed['late_minutes'],
                            'undertime_minutes' => $computed['undertime_minutes'],
                            'import_source'     => $filename,
                        ]
                    );

                    Point::updateOrCreate(
                        ['userid' => $employee->id, 't_date' => $date],
                        ['acc_points' => round((0.42 / 8) * $computed['total_hours'], 4)]
                    );

                    $imported++;
                }
            }
        });

        return redirect()
            ->route('hr.attendance.index')
            ->with('import_summary', [
                'filename'  => $filename,
                'raw_rows'  => count($rows),
                'raw_saved' => $rawSaved,
                'imported'  => $imported,
                'skipped'   => $skipped,
            ]);
    }

    // ─────────────────────────────────────────────────────────────
    // GET: Generate DTR PDF
    // ─────────────────────────────────────────────────────────────
    public function printDtr(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|integer',
            'month'       => 'required|date_format:Y-m',
        ]);

        $employee = Employee::with('user')->findOrFail($request->employee_id);
        [$year, $mon] = explode('-', $request->month);

        $daysInMonth = Carbon::create($year, $mon, 1)->daysInMonth;
        $monthLabel  = Carbon::create($year, $mon, 1)->format('F Y');

        // Fetch attendance for the month, keyed by Y-m-d
        $attendanceMap = Attendance::where('user_id', $employee->id)
            ->whereYear('t_date', $year)
            ->whereMonth('t_date', $mon)
            ->get()
            ->keyBy(fn ($a) => Carbon::parse($a->t_date)->format('Y-m-d'));

        $days           = [];
        $totalUtMins    = 0;
        $totalLateMins  = 0;
        $totalHoursSum  = 0.0;

        for ($d = 1; $d <= $daysInMonth; $d++) {
            $date      = Carbon::create($year, $mon, $d);
            $dateKey   = $date->format('Y-m-d');
            $att       = $attendanceMap->get($dateKey);
            $isWeekend = $date->isWeekend();
            $isHoliday = false;
            $absent    = ! $att && ! $isWeekend && ! $isHoliday;

            // ── Recompute from raw time data so we never rely on
            //    possibly-null DB columns from old imports ──────────
            $undertimeMinutes = 0;
            $lateMinutes      = 0;
            $totalHours       = 0.0;
            $amIn = $amOut = $pmIn = $pmOut = null;

            if ($att) {
                // Read stored time strings
                $amIn  = $att->am_time_in  ?? null;
                $amOut = $att->am_time_out ?? null;
                $pmIn  = $att->pm_time_in  ?? null;
                $pmOut = $att->pm_time_out ?? null;

                // Determine first and last punch for computation.
                // Always use am_time_in as the start (earliest arrival)
                // and pm_time_out as the end (latest departure).
                // Fall back to whatever is available.
                $firstTime = $amIn ?? $pmIn;   // am_in first, pm_in if no morning punch
                $lastTime  = $pmOut ?? $amOut; // pm_out first, am_out if no afternoon punch

                if ($firstTime && $lastTime && $firstTime !== $lastTime) {
                    // Recompute via the service — always accurate
                    $computed = $this->flexi->computeDay(
                        $dateKey,
                        Carbon::parse("$dateKey $firstTime"),
                        Carbon::parse("$dateKey $lastTime"),
                    );
                    $lateMinutes      = $computed['late_minutes'];
                    $undertimeMinutes = $computed['undertime_minutes'];
                    $totalHours       = $computed['total_hours'];
                } elseif ($firstTime) {
                    // Only 1 punch — full undertime
                    $lateMinutes      = Carbon::parse("$dateKey $firstTime")->gt(Carbon::parse("$dateKey 09:00"))
                        ? (int) Carbon::parse("$dateKey 09:00")->diffInMinutes(Carbon::parse("$dateKey $firstTime"))
                        : 0;
                    $undertimeMinutes = 480;
                    $totalHours       = 0.0;
                }

                // Accumulate totals (weekdays only)
                if (! $isWeekend && ! $isHoliday) {
                    $totalUtMins   += $undertimeMinutes;
                    $totalLateMins += $lateMinutes;
                    $totalHoursSum += $totalHours;
                }
            }

            $days[] = [
                'day'          => $d,
                'date'         => $dateKey,
                'is_weekend'   => $isWeekend,
                'is_holiday'   => $isHoliday,
                'holiday_name' => null,
                'absent'       => $absent,
                // Individual session times — for the 4 DTR columns
                'am_time_in'   => $amIn,
                'am_time_out'  => $amOut,
                'pm_time_in'   => $pmIn,
                'pm_time_out'  => $pmOut,
                // Undertime for this day
                'undertime'    => $undertimeMinutes,
                'late'         => $lateMinutes,
                'total_hours'  => $totalHours,
            ];
        }

        $dtr = [
            'employee'        => $employee,
            'month'           => $monthLabel,
            'year'            => $year,
            'days'            => $days,
            // Pre-computed totals passed directly to blade
            'total_undertime' => $totalUtMins,
            'total_late'      => $totalLateMins,
            'total_hours'     => $totalHoursSum,
        ];

        $pdf      = app('dompdf.wrapper');
        $safeName = str_replace([',', ' '], ['', '_'], $employee->full_name);

        $pdf->loadView('hr.attendance.dtr.pdf', compact('dtr'))
            ->setPaper('letter', 'portrait');

        return $pdf->stream("DTR_{$safeName}_{$request->month}.pdf");
    }

    // ─────────────────────────────────────────────────────────────
    // GET: Import history
    // ─────────────────────────────────────────────────────────────
    public function history()
    {
        $files = AttendanceLog::select(
            'source_file',
            DB::raw('COUNT(*) as punch_count'),
            DB::raw('MAX(created_at) as imported_at')
        )
            ->groupBy('source_file')
            ->orderByDesc('imported_at')
            ->get();

        return view('hr.zkteco.history', compact('files'));
    }
}
