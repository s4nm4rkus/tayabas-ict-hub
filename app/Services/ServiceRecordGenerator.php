<?php

namespace App\Services;

use App\Models\EmploymentHistory;
use App\Models\Salary;
use App\Models\ServiceRecord;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ServiceRecordGenerator
{
    /**
     * Regenerate all auto-generated service record rows for an employee.
     *
     * @param int $serviceRecordUserId  tbl_employee_info.id (e.g. 379) — FK in tbl_service_rec
     * @param int|null $historyUserId   users.id (e.g. 390) — FK in tbl_employment_history
     *                                  If null, assumes same as $serviceRecordUserId
     */
    public function generate(int $serviceRecordUserId, ?int $historyUserId = null): void
    {
        $historyUserId = $historyUserId ?? $serviceRecordUserId;

        $histories = EmploymentHistory::where('user_id', $historyUserId)
            ->orderBy('effective_date')
            ->get();

        if ($histories->isEmpty()) {
            return;
        }

        DB::transaction(function () use ($serviceRecordUserId, $histories) {
            ServiceRecord::where('user_id', $serviceRecordUserId)
                ->where('is_auto_generated', true)
                ->delete();

            $rows = [];
            foreach ($histories as $history) {
                $rows = array_merge($rows, $this->expandHistory($history));
            }

            foreach ($rows as $row) {
                ServiceRecord::create(array_merge($row, [
                    'user_id'           => $serviceRecordUserId, // 379
                    'is_auto_generated' => true,
                ]));
            }
        });
    }

    /**
     * Expand a single history entry into annual service record rows.
     */
    private function expandHistory(EmploymentHistory $history): array
    {
        $rows   = [];
        $start  = $history->effective_date->copy();
        $end    = $history->end_date ? $history->end_date->copy() : Carbon::today();
        $anchor = $history->step_anchor
            ? $history->step_anchor->copy()
            : $start->copy();

        $cursor = $start->copy();

        while ($cursor->lessThanOrEqualTo($end)) {
            $nextAnniversary = $this->nextAnniversary($cursor, $start);

            $periodEnd = $nextAnniversary->lessThanOrEqualTo($end)
                ? $nextAnniversary->copy()->subDay()
                : $end->copy();

            $step         = min($this->computeStep($history->salary_step, $anchor, $cursor), 8);
            $annualSalary = $this->computeAnnualSalary($history->salary_grade, $step);

            $isOpenPeriod = is_null($history->end_date) && $periodEnd->isSameDay($end);

            $rows[] = [
                'inclu_from'     => $cursor->toDateString(),
                'inclu_to'       => $isOpenPeriod ? null : $periodEnd->toDateString(),
                'position'       => $history->position,
                'designation'    => $history->sub_position ?? $history->position,
                'service_status' => $history->status_appoint,
                'salary_grade'   => $history->salary_grade,
                'salary_step'    => $step,
                'annual_salary'  => $annualSalary,
                'station'        => $history->station,
                'branch'         => 'National',
                'separation'     => ($history->end_date && $periodEnd->isSameDay($end))
                    ? ($history->change_reason !== 'ORIGINAL' ? $history->change_reason : 'NONE')
                    : 'NONE',
                'change_reason'  => $history->change_reason,
            ];

            if ($isOpenPeriod) {
                break;
            }

            $cursor = $nextAnniversary->copy();

            if ($cursor->greaterThan($end)) {
                break;
            }
        }

        return $rows;
    }

    private function nextAnniversary(Carbon $cursor, Carbon $start): Carbon
    {
        $next = $cursor->copy()->setMonth($start->month)->setDay($start->day);

        if ($next->lessThanOrEqualTo($cursor)) {
            $next->addYear();
        }

        return $next;
    }

    private function computeStep(int $baseStep, Carbon $anchor, Carbon $atDate): int
    {
        if ($atDate->lessThan($anchor)) {
            return $baseStep;
        }

        $yearsElapsed = $anchor->copy()->startOfDay()->diffInYears($atDate->copy()->startOfDay());
        $increments   = (int) floor($yearsElapsed / 3);

        return min($baseStep + $increments, 8);
    }

    private function computeAnnualSalary(?int $salaryGrade, int $step): ?float
    {
        if (!$salaryGrade) {
            return null;
        }

        $monthly = Salary::where('salary_grade', $salaryGrade)->value('step_' . $step);

        return $monthly ? (float) $monthly * 12 : null;
    }

    /**
     * Preview rows without saving.
     */
    public function preview(int $serviceRecordUserId, ?int $historyUserId = null): array
    {
        $historyUserId = $historyUserId ?? $serviceRecordUserId;

        $histories = EmploymentHistory::where('user_id', $historyUserId)
            ->orderBy('effective_date')
            ->get();

        $rows = [];
        foreach ($histories as $history) {
            $rows = array_merge($rows, $this->expandHistory($history));
        }

        return $rows;
    }
}
