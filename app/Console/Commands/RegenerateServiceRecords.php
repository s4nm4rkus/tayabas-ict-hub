<?php

namespace App\Console\Commands;

use App\Models\Employee;
use App\Models\EmploymentHistory;
use App\Models\EmploymentInfo;
use App\Models\Salary;
use App\Services\ServiceRecordGenerator;
use Carbon\Carbon;
use Illuminate\Console\Command;

class RegenerateServiceRecords extends Command
{
    protected $signature   = 'service-records:regenerate
                                {--user_id= : Run for a single employee (user_id from users table)}
                                {--dry-run  : Preview what would change without saving}';

    protected $description = 'Regenerate all auto-generated service records and sync salary step increments to employment info.';

    public function __construct(private ServiceRecordGenerator $generator)
    {
        parent::__construct();
    }

    public function handle(): void
    {
        $dryRun    = $this->option('dry-run');
        $singleUid = $this->option('user_id');

        $this->info($dryRun ? '🔍 DRY RUN — no changes will be saved.' : '⚙️  Running service record regeneration...');
        $this->newLine();

        // Get employees to process
        $query = Employee::with(['employment']);
        if ($singleUid) {
            $query->where('user_id', $singleUid);
        }
        $employees = $query->get();

        if ($employees->isEmpty()) {
            $this->warn('No employees found.');
            return;
        }

        $updated  = 0;
        $skipped  = 0;
        $noInfo   = 0;

        foreach ($employees as $employee) {

            // tbl_employment_info uses employee.id as FK
            $empId = $employee->user_id;

            if (!$employee->employment) {
                $this->line("  <fg=gray>SKIP</> [{$employee->user_id}] {$employee->full_name} — no employment info");
                $noInfo++;
                continue;
            }

            // Get the current active history entry
            $currentHistory = EmploymentHistory::where('user_id', $empId)
                ->whereNull('end_date')
                ->latest('effective_date')
                ->first();

            if (!$currentHistory) {
                $this->line("  <fg=gray>SKIP</> [{$employee->user_id}] {$employee->full_name} — no history entry");
                $skipped++;
                continue;
            }

            // Compute what the step SHOULD be today based on anchor + 3yr rule
            $anchor      = Carbon::parse($currentHistory->step_anchor);
            $baseStep    = (int) $currentHistory->salary_step;
            $yearsElapsed = $anchor->diffInYears(Carbon::today());
            $increments  = (int) floor($yearsElapsed / 3);
            $computedStep = min($baseStep + $increments, 8);

            $currentStep = (int) $employee->employment->salary_step;

            $stepChanged = $computedStep !== $currentStep;

            if ($stepChanged) {
                $this->line(
                    "  <fg=green>UPDATE</> [{$employee->user_id}] {$employee->full_name} — " .
                    "Step {$currentStep} → Step {$computedStep} " .
                    "(SG {$currentHistory->salary_grade}, anchor: {$anchor->format('M d, Y')})"
                );

                if (!$dryRun) {
                    // Update employment info with new step
                    EmploymentInfo::where('user_id', $empId)->update([
                        'salary_step' => $computedStep,
                    ]);
                }

                $updated++;
            } else {
                $this->line(
                    "  <fg=gray>OK</>     [{$employee->user_id}] {$employee->full_name} — " .
                    "Step {$currentStep} is current"
                );
            }

            // Always regenerate service records to keep rows fresh
            if (!$dryRun) {
                $empId    = $employee->id;       // 379 — service records
                $usersId  = $employee->user_id;  // 390 — history
                $this->generator->generate($empId, $usersId);
            }
        }

        $this->newLine();
        $this->info("Done. Updated: {$updated} | Skipped: {$skipped} | No info: {$noInfo}");

        if ($dryRun) {
            $this->warn('This was a dry run. Run without --dry-run to apply changes.');
        }
    }
}
