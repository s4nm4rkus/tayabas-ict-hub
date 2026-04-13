<?php

namespace App\Console\Commands;

use App\Models\Attendance;
use App\Models\Point;
use Illuminate\Console\Command;

class ProcessLeavePoints extends Command
{
    protected $signature   = 'leavepoints:process';
    protected $description = 'Calculate leave points from attendance records';

    public function handle(): void
    {
        $records = Attendance::all();

        foreach ($records as $record) {
            $totalHours = (float) $record->total_hours;
            $accPoints  = (0.42 / 8) * $totalHours;

            Point::updateOrCreate(
                [
                    'userid' => $record->user_id,
                    't_date' => $record->t_date,
                ],
                ['acc_points' => round($accPoints, 4)]
            );
        }

        $this->info('Leave points processed successfully.');
    }
}