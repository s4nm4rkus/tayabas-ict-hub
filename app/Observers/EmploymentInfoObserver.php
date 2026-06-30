<?php

namespace App\Observers;

use App\Models\Employee;
use App\Models\EmploymentHistory;
use App\Models\EmploymentInfo;
use App\Services\ServiceRecordGenerator;
use Illuminate\Support\Facades\Auth;

class EmploymentInfoObserver
{
    public function created(EmploymentInfo $info): void
    {
        $this->createInitialHistoryIfNeeded($info);
    }

    public function updated(EmploymentInfo $info): void
    {
        $this->createInitialHistoryIfNeeded($info);
    }

    private function createInitialHistoryIfNeeded(EmploymentInfo $info): void
    {
        // $info->user_id = tbl_employee_info.id (e.g. 379)
        $employee = Employee::where('id', $info->user_id)->first();
        if (!$employee) {
            return;
        }

        $usersId    = $employee->user_id; // 390 — used by tbl_employment_history
        $employeeId = $employee->id;      // 379 — used by tbl_service_rec

        // Only create if no history exists yet
        $exists = EmploymentHistory::where('user_id', $usersId)->exists();
        if ($exists) {
            return;
        }

        $effectiveDate = $info->salary_effect_date
            ?? $info->date_orig_appoint
            ?? now()->toDateString();

        EmploymentHistory::create([
            'user_id'        => $usersId,  // 390
            'position'       => $info->position,
            'sub_position'   => $info->sub_position,
            'salary_grade'   => $info->salary_grade,
            'salary_step'    => $info->salary_step ?? 1,
            'nature_appoint' => $info->nature_appoint,
            'status_appoint' => $info->status_appoint,
            'station'        => $info->school_office_assign,
            'effective_date' => $effectiveDate,
            'end_date'       => null,
            'change_reason'  => 'ORIGINAL',
            'step_anchor'    => $effectiveDate,
            'created_by'     => Auth::id(),
        ]);

        // generate(employeeId=379, historyUserId=390)
        app(ServiceRecordGenerator::class)->generate($employeeId, $usersId);
    }
}
