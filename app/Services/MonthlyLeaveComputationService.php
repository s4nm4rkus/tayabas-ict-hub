<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\MonthlyLeaveCredit;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class MonthlyLeaveComputationService
{
    public function __construct(protected LeaveCreditService $leaveCreditService)
    {
    }

    /**
     * Compute and apply VL/SL earning for one employee, for one year/month.
     *
     * Formula basis: CSC MC No. 41 s.1998 Sec. 27 — 1 day VL + 1 day SL for
     * every 24 days of actual service. A full month is treated as 30
     * calendar days (30/24 = 1.25, matching the confirmed flat monthly
     * rate). Partial months (new hire or separation within the month) use
     * calendar_days_employed / 24, capped at 30 — mathematically
     * continuous with the full-month rate, no separate branching needed.
     *
     * LWOP is NOT yet tracked anywhere in the system (no feature exists
     * for it). Every record computed here assumes zero LWOP and is
     * explicitly labeled as such — this may over-credit an employee who
     * had real unpaid absences that month. HR should use the manual
     * adjustment tool (LeaveCreditService::manualAdjustment) to correct
     * any known case until LWOP tracking is built.
     *
     * Idempotent: refuses to run twice for the same employee+year+month
     * (enforced by both this check and the DB unique constraint).
     *
     * @throws RuntimeException if already computed, employee ineligible,
     *         not employed that month, or has no opening balance yet.
     */
    public function computeForEmployee(int $employeeId, int $year, int $month, ?int $triggeredBy = null): MonthlyLeaveCredit
    {
        return DB::transaction(function () use ($employeeId, $year, $month, $triggeredBy) {

            $alreadyComputed = MonthlyLeaveCredit::where('employee_id', $employeeId)
                ->where('year', $year)
                ->where('month', $month)
                ->lockForUpdate()
                ->exists();

            if ($alreadyComputed) {
                throw new RuntimeException(
                    "Employee {$employeeId} already has a computed record for {$year}-{$month}. ".
                    'Re-running is not yet supported — this is a deliberate safeguard against double-crediting.'
                );
            }

            $employee = Employee::with('employment')->find($employeeId);

            if (! $employee) {
                throw new RuntimeException("Employee {$employeeId} not found.");
            }

            $eligibility = $this->checkEligibility($employee);
            if ($eligibility !== null) {
                throw new RuntimeException("Employee {$employeeId} skipped: {$eligibility}");
            }

            $hireDate       = $employee->employment?->date_orig_appoint;
            $separationDate = $employee->employment?->separation_date;

            if (! $hireDate) {
                throw new RuntimeException(
                    "Employee {$employeeId} has no recorded original appointment date — cannot determine employment span."
                );
            }

            $monthStart = Carbon::create($year, $month, 1)->startOfDay();
            $monthEnd   = $monthStart->copy()->endOfMonth()->startOfDay();

            $employmentStart = $hireDate->greaterThan($monthStart) ? $hireDate->copy()->startOfDay() : $monthStart->copy();
            $employmentEnd   = ($separationDate && $separationDate->lessThan($monthEnd))
                ? $separationDate->copy()->startOfDay()
                : $monthEnd->copy();

            if ($employmentStart->greaterThan($employmentEnd)) {
                throw new RuntimeException(
                    "Employee {$employeeId} was not employed at all during {$year}-{$month}."
                );
            }

            $daysEmployed = min((int) $employmentStart->diffInDays($employmentEnd) + 1, 30);

            $earned = round($daysEmployed / 24, 3);

            $record = MonthlyLeaveCredit::create([
                'employee_id'          => $employeeId,
                'year'                 => $year,
                'month'                => $month,
                'actual_service_days'  => $daysEmployed,
                'lwop_days'            => 0, // Not yet tracked — see class docblock
                'vl_earned'            => $earned,
                'sl_earned'            => $earned,
                'computation_source'   => 'ORL Sec. 27: days_employed/24, capped 30. LWOP not yet tracked (assumed 0).',
                'computed_by'          => $triggeredBy,
                'computed_at'          => now(),
            ]);

            $this->leaveCreditService->recordMonthlyEarning(
                employeeId: $employeeId,
                vlEarned: $earned,
                slEarned: $earned,
                effectiveDate: $monthEnd->toDateString(),
                createdBy: $triggeredBy,
            );

            return $record;
        });
    }

    /**
     * Run computation for every eligible employee for one year/month.
     * Employees who fail (already computed, ineligible, no balance, etc.)
     * are collected as skips rather than aborting the whole batch.
     *
     * @return array{computed: int, skipped: array<int, string>}
     */
    public function computeForMonth(int $year, int $month, ?int $triggeredBy = null): array
    {
        $employees = Employee::with('employment')->get();

        $computed = 0;
        $skipped  = [];

        foreach ($employees as $employee) {
            try {
                $this->computeForEmployee($employee->id, $year, $month, $triggeredBy);
                $computed++;
            } catch (RuntimeException $e) {
                $skipped[$employee->id] = $e->getMessage();
            }
        }

        return ['computed' => $computed, 'skipped' => $skipped];
    }

    /**
     * Eligibility gate — Permanent, Non-Teaching only for v1, matching the
     * scope decision made earlier in this project. Returns null if
     * eligible, or a human-readable reason string if not.
     */
    private function checkEligibility(Employee $employee): ?string
    {
        $statusAppoint = $employee->employment?->status_appoint;

        if ($statusAppoint !== 'Permanent') {
            return "not eligible for automated computation yet (status_appoint = '{$statusAppoint}', only Permanent is supported)";
        }

        $position = $employee->employment?->position;
        $roleCat  = \App\Models\Role::where('role_desc', $position)->value('role_cat');

        if ($roleCat !== 'Non-Teaching') {
            return "not eligible for automated computation yet (position '{$position}' resolved to role_cat '{$roleCat}', only Non-Teaching is supported)";
        }

        return null;
    }
}
