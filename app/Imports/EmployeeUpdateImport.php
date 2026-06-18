<?php

namespace App\Imports;

use App\Models\Employee;
use App\Models\EmploymentInfo;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EmployeeUpdateImport implements SkipsEmptyRows, ToCollection, WithHeadingRow
{
    public array $errors = [];

    public int $updated = 0;

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
                ], fn ($v) => ! is_null($v) && $v !== '');

                if (! empty($employeeUpdate)) {
                    $employee->update($employeeUpdate);
                }

                // ── Update Employment Info ─────────────────────────────────
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

                if (! empty($employmentUpdate)) {
                    EmploymentInfo::updateOrCreate(
                        ['user_id' => $employee->id],
                        $employmentUpdate
                    );
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
