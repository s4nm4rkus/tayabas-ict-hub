<?php

namespace App\Console\Commands;

use App\Models\Attendance;
use App\Models\AttendanceLog;
use App\Models\Employee;
use App\Models\Point;
use App\Services\FlexiAttendanceService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ReprocessAttendanceLogs extends Command
{
    protected $signature   = 'attendance:reprocess
                                {--month= : Only reprocess a specific month e.g. 2026-05}
                                {--emp=   : Only reprocess a specific employee_no}
                                {--dry-run : Show what would change without saving}';

    protected $description = 'Reprocess raw attendance_logs through the current FlexiAttendanceService logic. '
        . 'Use this after updating slotting rules to fix existing records.';

    public function __construct(protected FlexiAttendanceService $flexi)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $isDry   = $this->option('dry-run');
        $month   = $this->option('month');   // e.g. "2026-05"
        $empNo   = $this->option('emp');

        $this->info($isDry ? '--- DRY RUN — no changes will be saved ---' : 'Reprocessing attendance logs...');

        // ── Build query on raw logs ──────────────────────────────
        $query = AttendanceLog::query()->orderBy('punch_time');

        if ($month) {
            [$y, $m] = explode('-', $month);
            $query->whereYear('punch_time', $y)->whereMonth('punch_time', $m);
        }

        if ($empNo) {
            $query->where('emp_code', $empNo);
        }

        // Group in PHP — groupBy on the query would need raw SQL
        $logs = $query->get();

        if ($logs->isEmpty()) {
            $this->warn('No logs found for the given filters.');
            return 0;
        }

        // ── Bucket by emp_code → date ────────────────────────────
        $buckets = [];
        foreach ($logs as $log) {
            $date = Carbon::parse($log->punch_time)->format('Y-m-d');
            $buckets[$log->emp_code][$date][] = Carbon::parse($log->punch_time);
        }

        $updated  = 0;
        $skipped  = 0;
        $bar      = $this->output->createProgressBar(
            array_sum(array_map('count', $buckets))
        );

        DB::transaction(function () use ($buckets, $isDry, &$updated, &$skipped, $bar) {
            foreach ($buckets as $empCode => $days) {
                $employee = Employee::where('employee_no', $empCode)->first();

                if (! $employee) {
                    $this->newLine();
                    $this->warn("  Employee #{$empCode} not found — skipping.");
                    $skipped += count($days);
                    $bar->advance(count($days));
                    continue;
                }

                foreach ($days as $date => $punches) {
                    usort($punches, fn ($a, $b) => $a->timestamp <=> $b->timestamp);

                    $firstPunch = $punches[0];
                    $lastPunch  = count($punches) > 1 ? end($punches) : null;

                    $computed = $this->flexi->computeDay($date, $firstPunch, $lastPunch, $punches);

                    if ($isDry) {
                        // Show what the new values would be
                        $this->newLine();
                        $this->line(sprintf(
                            '  %s | %s | am_in=%-5s am_out=%-5s pm_in=%-5s pm_out=%-5s | hrs=%s late=%dm ut=%dm',
                            $employee->full_name,
                            $date,
                            $computed['am_time_in']  ?? '—',
                            $computed['am_time_out'] ?? '—',
                            $computed['pm_time_in']  ?? '—',
                            $computed['pm_time_out'] ?? '—',
                            $computed['total_hours'],
                            $computed['late_minutes'],
                            $computed['undertime_minutes'],
                        ));
                    } else {
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
                            ]
                        );

                        Point::updateOrCreate(
                            ['userid' => $employee->id, 't_date' => $date],
                            ['acc_points' => round((0.42 / 8) * $computed['total_hours'], 4)]
                        );

                        $updated++;
                    }

                    $bar->advance();
                }
            }
        });

        $bar->finish();
        $this->newLine(2);

        if ($isDry) {
            $this->info('Dry run complete. Run without --dry-run to apply changes.');
        } else {
            $this->info("Done. {$updated} attendance record(s) updated, {$skipped} employee(s) skipped.");
        }

        return 0;
    }
}
