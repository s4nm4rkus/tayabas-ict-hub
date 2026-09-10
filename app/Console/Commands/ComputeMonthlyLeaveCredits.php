<?php

namespace App\Console\Commands;

use App\Services\MonthlyLeaveComputationService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ComputeMonthlyLeaveCredits extends Command
{
    /**
     * php artisan leave:compute-monthly
     *   → computes LAST month (the month that just ended)
     * php artisan leave:compute-monthly 2026 8
     *   → computes a specific year/month
     */
    protected $signature = 'leave:compute-monthly {year? : Year, e.g. 2026} {month? : Month 1-12}';

    protected $description = 'Run automatic VL/SL monthly earning computation for all eligible employees.';

    public function handle(MonthlyLeaveComputationService $service): int
    {
        $year  = (int) ($this->argument('year') ?? now()->subMonthNoOverflow()->year);
        $month = (int) ($this->argument('month') ?? now()->subMonthNoOverflow()->month);

        $label = Carbon::create($year, $month, 1)->format('F Y');
        $this->info("Running monthly leave credit computation for {$label}...");

        $result = $service->computeForMonth($year, $month, triggeredBy: null);

        $this->info("Computed: {$result['computed']} employee(s).");
        $this->warn('Skipped: '.count($result['skipped']).' employee(s).');

        // Log the full detail — this is currently the only place to review
        // results of an AUTOMATIC (unattended) run, since there's no UI
        // watching it happen. Check storage/logs/laravel.log after each
        // scheduled run, or run this command manually anytime to see
        // results directly in the terminal.
        \Illuminate\Support\Facades\Log::info("Monthly leave credit computation — {$label}", [
            'computed' => $result['computed'],
            'skipped'  => $result['skipped'],
        ]);

        foreach ($result['skipped'] as $employeeId => $reason) {
            $this->line("  [skipped] Employee {$employeeId}: {$reason}");
        }

        return self::SUCCESS;
    }
}
