<?php

namespace App\Imports;

use App\Models\Employee;
use App\Models\EmploymentHistory;
use App\Models\EmploymentInfo;
use App\Models\User;
use App\Services\ServiceRecordGenerator;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EmployeeUpdateImport implements SkipsEmptyRows, ToCollection, WithHeadingRow
{
    public array $errors = [];

    public int $updated = 0;

    public function __construct(
        private ServiceRecordGenerator $generator
    ) {
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            try {
                $email = trim($row['gov_email'] ?? '');

                if (empty($email)) {
                    $this->errors[] = 'Row ' . ($index + 2) . ': gov_email is empty — skipped.';
                    continue;
                }

                // Find user by email
                $user = User::where('username', $email)->first();

                if (! $user) {
                    $this->errors[] = 'Row ' . ($index + 2) . ": {$email} not found — skipped.";
                    continue;
                }

                // Find employee record
                $employee = Employee::where('user_id', $user->id)->first();

                if (! $employee) {
                    $this->errors[] = 'Row ' . ($index + 2) . ": No employee record for {$email} — skipped.";
                    continue;
                }

                // ── Update User ────────────────────────────────────────────
                $userUpdate = [];
                if (! empty($row['position'])) {
                    $userUpdate['user_pos'] = $row['position'];
                }
                if (! empty($userUpdate)) {
                    $user->update($userUpdate);
                }

                // ── Update Employee ────────────────────────────────────────
                $employeeUpdate = array_filter([
                    'last_name'      => $row['last_name']      ?? null,
                    'first_name'     => $row['first_name']     ?? null,
                    'middle_name'    => $row['middle_name']    ?? null,
                    'ex_name'        => $row['extension']      ?? null,
                    'gender'         => $row['gender']         ?? null,
                    'birthdate'      => $this->parseDate($row['birthdate'] ?? null),
                    'place_of_birth' => $row['place_of_birth'] ?? null,
                    'contact_num'    => $row['contact_num']    ?? null,
                    'employee_no'    => $row['employee_no']    ?? null,
                    'philhealth'     => $row['philhealth']     ?? null,
                    'pagibig'        => $row['pagibig']        ?? null,
                    'TIN'            => $row['tin']            ?? null,
                    'street'         => $row['street']         ?? null,
                    'street_brgy'    => $row['barangay']       ?? null,
                    'municipality'   => $row['municipality']   ?? null,
                    'province'       => $row['province']       ?? null,
                    'region'         => $row['region']         ?? null,
                    'bp_no'          => $row['bp_number']      ?? null,
                ], fn ($v) => ! is_null($v) && $v !== '');

                if (! empty($employeeUpdate)) {
                    $employee->update($employeeUpdate);
                }

                // ── Update Employment Info (current snapshot) ──────────────
                // Note: nature_appoint, vice, vice_reason, designated_from,
                // designated_to, school_detailed_office_assign are no longer
                // in the CSV/template. $row[...] will be missing, so these
                // resolve to null and are dropped by array_filter — existing
                // DB values for those columns are left untouched.
                $employmentUpdate = array_filter([
                    'position'                      => $row['position']                       ?? null,
                    'sub_position'                  => $row['sub_position']                   ?? null,
                    'date_orig_appoint'             => $this->parseDate($row['date_orig_appoint'] ?? null),
                    'salary_grade'                  => $row['salary_grade']                   ?? null,
                    'salary_step'                   => $row['salary_step']                    ?? null,
                    'salary_effect_date'            => $this->parseDate($row['salary_effect_date'] ?? null),
                    'vice'                          => $row['vice']                            ?? null,
                    'vice_reason'                   => $row['vice_reason']                    ?? null,
                    'nature_appoint'                => $row['nature_appoint']                 ?? null,
                    'status_appoint'                => $row['status_appoint']                 ?? null,
                    'station_code'                  => $row['station_code']                   ?? null,
                    'plantilla_item_no'             => $row['plantilla_item_no']              ?? null,
                    'plantilla_inclu'               => $row['plantilla_inclu']                ?? null,
                    'school_office_assign'          => $row['school_office_assign']           ?? null,
                    'school_detailed_office_assign' => $row['school_detailed_office_assign']  ?? null,
                    'designated_from'               => $this->parseDate($row['designated_from'] ?? null),
                    'designated_to'                 => $this->parseDate($row['designated_to']   ?? null),
                    'separation'                    => $row['separation']                     ?? null,
                    'separation_date'               => $this->parseDate($row['separation_date'] ?? null),
                    'head'                          => $row['head']                           ?? null,
                ], fn ($v) => ! is_null($v) && $v !== '');

                $employmentInfo = null;

                if (! empty($employmentUpdate)) {
                    $employmentInfo = EmploymentInfo::updateOrCreate(
                        ['user_id' => $employee->id],
                        $employmentUpdate
                    );
                }

                // ── date_last_promotion: real appointment event ──────────
                // Mirrors EmploymentHistoryController::store(): close the
                // current open history row, open a new one, refresh the
                // snapshot's salary_effect_date, regenerate service records.
                $lastPromotionDate = $this->parseDate($row['date_last_promotion'] ?? null);

                if ($lastPromotionDate) {
                    // Make sure we have the snapshot (already merged with
                    // this row's other changes, if any were provided above).
                    $employmentInfo = $employmentInfo
                        ?? EmploymentInfo::where('user_id', $employee->id)->first();

                    $employeeId = $employee->id; // tbl_employment_info / tbl_service_rec FK
                    $userId     = $user->id;     // tbl_employment_history FK

                    DB::transaction(function () use (
                        $employmentInfo,
                        $employeeId,
                        $userId,
                        $lastPromotionDate
                    ) {
                        // 1. Close the current active history row
                        $current = EmploymentHistory::where('user_id', $userId)
                            ->whereNull('end_date')
                            ->latest('effective_date')
                            ->first();

                        if ($current) {
                            $current->update([
                                'end_date' => Carbon::parse($lastPromotionDate)
                                    ->subDay()
                                    ->toDateString(),
                            ]);
                        }

                        // 2. Resolve field values from the (already-updated)
                        // snapshot, falling back to the closed history row.
                        $position      = $employmentInfo->position       ?? $current?->position;
                        $subPosition   = $employmentInfo->sub_position   ?? $current?->sub_position;
                        $salaryGrade   = $employmentInfo->salary_grade   ?? $current?->salary_grade;
                        $salaryStep    = $employmentInfo->salary_step    ?? $current?->salary_step;
                        $natureAppoint = $employmentInfo->nature_appoint ?? $current?->nature_appoint;
                        $statusAppoint = $employmentInfo->status_appoint ?? $current?->status_appoint;
                        $station       = $employmentInfo->school_office_assign ?? $current?->station;

                        // 3. Create the new history row.
                        // PROMOTION always resets the step anchor to the new date.
                        EmploymentHistory::create([
                            'user_id'        => $userId,
                            'position'       => $position,
                            'sub_position'   => $subPosition,
                            'salary_grade'   => $salaryGrade,
                            'salary_step'    => $salaryStep,
                            'nature_appoint' => $natureAppoint,
                            'status_appoint' => $statusAppoint,
                            'station'        => $station,
                            'effective_date' => $lastPromotionDate,
                            'end_date'       => null,
                            'change_reason'  => 'PROMOTION',
                            'step_anchor'    => $lastPromotionDate,
                            'created_by'     => Auth::id(),
                        ]);

                        // 4. Refresh the snapshot's effective date to match.
                        EmploymentInfo::where('user_id', $employeeId)->update([
                            'salary_effect_date' => $lastPromotionDate,
                        ]);

                        // 5. Regenerate service records.
                        $this->generator->generate($employeeId, $userId);
                    });
                }

                $this->updated++;

            } catch (\Exception $e) {
                $this->errors[] = 'Row ' . ($index + 2) . ': ' . $e->getMessage();
            }
        }
    }

    private function parseDate(?string $date): ?string
    {
        if (empty($date)) {
            return null;
        }

        try {
            if (is_numeric($date) && (int) $date > 1000) {
                return Carbon::instance(
                    \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject((float) $date)
                )->format('Y-m-d');
            }
            if (preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', trim($date))) {
                return Carbon::createFromFormat('m/d/Y', trim($date))->format('Y-m-d');
            }
            return Carbon::parse(trim($date))->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }
}
