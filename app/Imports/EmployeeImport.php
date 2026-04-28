<?php

namespace App\Imports;

use App\Models\Employee;
use App\Models\EmploymentInfo;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EmployeeImport implements SkipsEmptyRows, ToCollection, WithHeadingRow
{
    public array $errors = [];

    public int $imported = 0;

    public array $passwords = [];

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            try {
                $email = trim($row['gov_email'] ?? '');

                if (empty($email)) {
                    $this->errors[] = 'Row '.($index + 2).': gov_email is empty — skipped.';

                    continue;
                }

                if (User::where('username', $email)->exists()) {
                    $this->errors[] = 'Row '.($index + 2).": {$email} already exists — skipped.";

                    continue;
                }

                $displayCode = $this->generateDisplayCode();
                $defaultPassword = $this->generateDefaultPassword($row->toArray());

                $user = User::create([
                    'user_id' => $displayCode,
                    'username' => $email,
                    'password' => Hash::make($defaultPassword),
                    'user_pos' => $row['position'] ?? 'Employee',
                    'user_stat' => 'Enabled',
                    'pass_change' => false,
                ]);

                $emp = Employee::create([
                    'user_id' => $user->id,
                    'last_name' => $row['last_name'] ?? null,
                    'first_name' => $row['first_name'] ?? null,
                    'middle_name' => $row['middle_name'] ?? null,
                    'ex_name' => $row['extension'] ?? null,
                    'gender' => $row['gender'] ?? null,
                    'birthdate' => $row['birthdate'] ?? null,
                    'place_of_birth' => $row['place_of_birth'] ?? null,
                    'contact_num' => $row['contact_num'] ?? null,
                    'gov_email' => $email,
                    'employee_no' => $row['employee_no'] ?? null,
                    'philhealth' => $row['philhealth'] ?? null,
                    'pagibig' => $row['pagibig'] ?? null,
                    'TIN' => $row['tin'] ?? null,
                    'street' => $row['street'] ?? null,
                    'street_brgy' => $row['barangay'] ?? null,
                    'municipality' => $row['municipality'] ?? null,
                    'province' => $row['province'] ?? null,
                    'region' => $row['region'] ?? null,
                    'date_encoded' => now(),
                ]);

                $this->imported++;
                $this->passwords[] = [
                    'name' => trim(($row['first_name'] ?? '').' '.($row['last_name'] ?? '')),
                    'email' => $email,
                    'id' => $displayCode,
                    'password' => $defaultPassword,
                ];

                // Auto-create employment record
                EmploymentInfo::create([
                    'user_id' => $emp->id,
                    'position' => $row['position'] ?? null,
                ]);

            } catch (\Exception $e) {
                $this->errors[] = 'Row '.($index + 2).': '.$e->getMessage();
            }
        }
    }

    private function generateDefaultPassword(array $row): string
    {
        $firstName = ucfirst(strtolower(trim($row['first_name'] ?? 'employee')));
        $birthdate = trim($row['birthdate'] ?? '');

        if (! empty($birthdate)) {
            try {
                $date = Carbon::parse($birthdate);

                return $firstName.$date->format('mdY');
            } catch (\Exception $e) {
                // fallback
            }
        }

        return $firstName.'ICThub@123';
    }

    private function generateDisplayCode(): string
    {
        $year = date('Y');
        $prefix = "ICTHUB-{$year}-";

        $last = User::where('user_id', 'like', "{$prefix}%")
            ->orderBy('user_id', 'desc')
            ->first();

        $next = $last
            ? str_pad((int) substr($last->user_id, -4) + 1, 4, '0', STR_PAD_LEFT)
            : '0001';

        return "{$prefix}{$next}";
    }
}
