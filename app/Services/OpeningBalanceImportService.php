<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\LeaveBalance;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use Throwable;

/**
 * Bulk entry of opening (starting) VL/SL balances.
 *
 * Rules (from the project spec, Section 37):
 *  - Every row is validated before anything is saved.
 *  - ALL-OR-NOTHING: if even one row has a problem, nothing is imported,
 *    so HR fixes the file and re-uploads the whole thing. (Importing only
 *    the "good" rows would make re-uploads fail with "already has a
 *    balance" for the rows that already went in.)
 *  - Employees are matched by gov_email, NOT by database id. The system has
 *    several different "id" columns that are easy to mix up, and HR would
 *    never know which one to type.
 *  - An employee who already has a balance is rejected — opening balance
 *    is one-time only; corrections go through Manual Adjustment.
 *  - Every created balance goes through LeaveCreditService::
 *    recordOpeningBalance(), so each one gets a proper ledger entry.
 */
class OpeningBalanceImportService
{
    public const REQUIRED_HEADERS = ['gov_email', 'vl_balance', 'sl_balance', 'as_of_date'];

    public function __construct(protected LeaveCreditService $leaveCreditService)
    {
    }

    /**
     * @return string[] required column names that are absent from the file
     */
    public function missingHeaders(Collection $rows): array
    {
        $present = collect($rows->first() ?? [])
            ->keys()
            ->map(fn ($k) => strtolower(trim((string) $k)))
            ->all();

        return array_values(array_diff(self::REQUIRED_HEADERS, $present));
    }

    /**
     * Validate every row. Writes nothing.
     *
     * @return array{total:int, valid:array<int,array>, errors:array<int,string[]>}
     *         'errors' is keyed by the row number as seen in the spreadsheet
     *         (row 1 = headings, first data row = 2).
     */
    public function validate(Collection $rows): array
    {
        $employeesByEmail = Employee::whereNotNull('gov_email')
            ->get(['id', 'gov_email', 'last_name', 'first_name', 'middle_name'])
            ->groupBy(fn ($e) => strtolower(trim($e->gov_email)));

        $hasBalance = LeaveBalance::pluck('employee_id')->flip();

        $valid  = [];
        $errors = [];
        $seen   = []; // email => first row number it appeared on
        $total  = 0;

        foreach ($rows->values() as $i => $row) {
            $rowNumber = $i + 2;
            $data = collect($row)->map(fn ($v) => is_string($v) ? trim($v) : $v);

            // Skip completely blank lines
            if ($data->filter(fn ($v) => $v !== null && $v !== '')->isEmpty()) {
                continue;
            }

            $total++;
            $problems = [];

            // ── Employee (matched by gov_email) ────────────────────────
            $email    = strtolower((string) ($data['gov_email'] ?? ''));
            $employee = null;

            if ($email === '') {
                $problems[] = 'gov_email is required.';
            } else {
                $matches = $employeesByEmail->get($email);

                if (! $matches) {
                    $problems[] = "No employee found with gov_email '{$email}'.";
                } elseif ($matches->count() > 1) {
                    $problems[] = "gov_email '{$email}' matches more than one employee record — fix the duplicate in Employee records first.";
                } else {
                    $employee = $matches->first();

                    if (isset($seen[$email])) {
                        $problems[] = "Same employee already listed on row {$seen[$email]}.";
                    } elseif ($hasBalance->has($employee->id)) {
                        $problems[] = 'Already has a leave balance. Opening balance is one-time only — use Manual Adjustment instead.';
                    }

                    $seen[$email] ??= $rowNumber;
                }
            }

            // ── Balances ───────────────────────────────────────────────
            $amounts = [];
            foreach (['vl_balance', 'sl_balance'] as $col) {
                $val = $data[$col] ?? null;

                if ($val === null || $val === '') {
                    $problems[] = "{$col} is required.";
                    continue;
                }
                if (! is_numeric($val)) {
                    $problems[] = "{$col} must be a number (got '{$val}').";
                    continue;
                }

                $num = (float) $val;

                if ($num < 0) {
                    $problems[] = "{$col} cannot be negative.";
                } elseif (abs($num - round($num, 3)) > 1e-9) {
                    $problems[] = "{$col} allows at most 3 decimal places.";
                } else {
                    $amounts[$col] = round($num, 3);
                }
            }

            // ── As-of date ─────────────────────────────────────────────
            $asOf = null;
            $raw  = $data['as_of_date'] ?? null;

            if ($raw === null || $raw === '') {
                $problems[] = 'as_of_date is required.';
            } else {
                $asOf = $this->parseDate($raw);

                if (! $asOf) {
                    $problems[] = "as_of_date '{$raw}' is not valid. Use YYYY-MM-DD (e.g. 2026-08-31), or a real date cell in Excel.";
                } elseif ($asOf->greaterThan(Carbon::today())) {
                    $problems[] = 'as_of_date cannot be in the future.';
                } elseif ($asOf->lessThan(Carbon::create(2000, 1, 1))) {
                    $problems[] = "as_of_date '{$asOf->toDateString()}' looks wrong (before year 2000).";
                }
            }

            // ── Notes ──────────────────────────────────────────────────
            $notes = $data['notes'] ?? null;
            if ($notes !== null && $notes !== '' && mb_strlen((string) $notes) > 200) {
                $problems[] = 'notes is longer than 200 characters.';
            }

            if ($problems) {
                $errors[$rowNumber] = $problems;
                continue;
            }

            $valid[] = [
                'row'         => $rowNumber,
                'employee_id' => $employee->id,
                'name'        => $employee->full_name,
                'vl'          => $amounts['vl_balance'],
                'sl'          => $amounts['sl_balance'],
                'as_of'       => $asOf->toDateString(),
                'notes'       => ($notes !== null && $notes !== '') ? (string) $notes : null,
            ];
        }

        return ['total' => $total, 'valid' => $valid, 'errors' => $errors];
    }

    /**
     * Create the opening balances for rows that already passed validate().
     * One transaction: if anything fails (e.g. someone else created a
     * balance for one of these employees a moment ago), NOTHING is saved
     * and the exception is rethrown for the controller to report.
     *
     * @throws \RuntimeException
     */
    public function import(array $validRows, ?int $userId): int
    {
        DB::transaction(function () use ($validRows, $userId) {
            foreach ($validRows as $r) {
                $this->leaveCreditService->recordOpeningBalance(
                    employeeId: $r['employee_id'],
                    vlBalance: $r['vl'],
                    slBalance: $r['sl'],
                    asOfDate: $r['as_of'],
                    createdBy: $userId,
                    description: $r['notes']
                        ? 'Bulk import: '.$r['notes']
                        : 'Bulk import of starting balance',
                );
            }
        });

        return count($validRows);
    }

    /**
     * Accepts YYYY-MM-DD text, or an Excel date cell (which arrives as a
     * serial number). Anything else is rejected on purpose: text like
     * 08/09/2026 could mean August 9 or September 8, and a wrong as-of date
     * would silently affect the "Earned" figure on Form 6.
     */
    private function parseDate(mixed $raw): ?Carbon
    {
        try {
            if (is_numeric($raw)) {
                return Carbon::instance(ExcelDate::excelToDateTimeObject((float) $raw))->startOfDay();
            }

            $text = (string) $raw;
            $d    = Carbon::createFromFormat('!Y-m-d', $text);

            return ($d && $d->format('Y-m-d') === $text) ? $d : null;
        } catch (Throwable) {
            return null;
        }
    }
}
