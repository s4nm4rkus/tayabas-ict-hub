<?php

namespace App\Services;

use App\Models\LeaveBalance;
use App\Models\LeaveCreditTransaction;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class LeaveCreditService
{
    /**
     * Create the starting VL/SL balance for an employee who has none yet.
     * This is a ONE-TIME operation per employee — use manualAdjustment()
     * for any correction after this point, never call this twice for the
     * same employee.
     */
    public function recordOpeningBalance(
        int $employeeId,
        float $vlBalance,
        float $slBalance,
        string $asOfDate,
        ?int $createdBy = null,
        ?string $description = null
    ): LeaveCreditTransaction {
        return DB::transaction(function () use ($employeeId, $vlBalance, $slBalance, $asOfDate, $createdBy, $description) {

            // Lock the row (if it exists) for the duration of this
            // transaction so two simultaneous requests can't both pass
            // the "does a balance already exist" check at the same time.
            $existing = LeaveBalance::where('employee_id', $employeeId)
                ->lockForUpdate()
                ->first();

            if ($existing) {
                throw new RuntimeException(
                    "Employee {$employeeId} already has a leave balance. ".
                    'Use manualAdjustment() to correct it instead of recordOpeningBalance().'
                );
            }

            if ($vlBalance < 0 || $slBalance < 0) {
                throw new RuntimeException('Opening balance cannot be negative.');
            }

            $balance = LeaveBalance::create([
                'employee_id' => $employeeId,
                'vl_balance'  => $vlBalance,
                'sl_balance'  => $slBalance,
                'as_of_date'  => $asOfDate,
                'updated_by'  => $createdBy,
            ]);

            return LeaveCreditTransaction::create([
                'employee_id'        => $employeeId,
                'transaction_type'   => LeaveCreditTransaction::TYPE_OPENING_BALANCE,
                'vl_amount'          => $vlBalance,
                'sl_amount'          => $slBalance,
                'vl_balance_before'  => 0,
                'sl_balance_before'  => 0,
                'vl_balance_after'   => $balance->vl_balance,
                'sl_balance_after'   => $balance->sl_balance,
                'reference_type'     => null,
                'reference_id'       => null,
                'effective_date'     => $asOfDate,
                'description'        => $description ?? 'Starting balance entry',
                'created_by'         => $createdBy,
                'approved_by'        => null,
            ]);
        });
    }

    /**
     * HR correction to an existing balance, per Section 41 — every override
     * must be reasoned and auditable, never a silent overwrite. $vlDelta /
     * $slDelta are signed (positive to add, negative to subtract).
     */
    public function manualAdjustment(
        int $employeeId,
        float $vlDelta,
        float $slDelta,
        string $effectiveDate,
        int $createdBy,
        string $reason,
        ?int $approvedBy = null
    ): LeaveCreditTransaction {
        if (trim($reason) === '') {
            throw new RuntimeException('A reason is required for every manual adjustment.');
        }

        return DB::transaction(function () use ($employeeId, $vlDelta, $slDelta, $effectiveDate, $createdBy, $reason, $approvedBy) {

            $balance = LeaveBalance::where('employee_id', $employeeId)
                ->lockForUpdate()
                ->first();

            if (! $balance) {
                throw new RuntimeException(
                    "No leave balance exists yet for employee {$employeeId}. ".
                    'Use recordOpeningBalance() first.'
                );
            }

            $vlBefore = (float) $balance->vl_balance;
            $slBefore = (float) $balance->sl_balance;
            $vlAfter  = $vlBefore + $vlDelta;
            $slAfter  = $slBefore + $slDelta;

            if ($vlAfter < 0 || $slAfter < 0) {
                throw new RuntimeException(
                    'This adjustment would result in a negative balance. '.
                    'Negative balances are not permitted without an explicit agency policy override (Section 15).'
                );
            }

            $balance->update([
                'vl_balance' => $vlAfter,
                'sl_balance' => $slAfter,
                'as_of_date' => $effectiveDate,
                'updated_by' => $createdBy,
            ]);

            return LeaveCreditTransaction::create([
                'employee_id'        => $employeeId,
                'transaction_type'   => LeaveCreditTransaction::TYPE_MANUAL_ADJUSTMENT,
                'vl_amount'          => $vlDelta,
                'sl_amount'          => $slDelta,
                'vl_balance_before'  => $vlBefore,
                'sl_balance_before'  => $slBefore,
                'vl_balance_after'   => $vlAfter,
                'sl_balance_after'   => $slAfter,
                'reference_type'     => null,
                'reference_id'       => null,
                'effective_date'     => $effectiveDate,
                'description'        => $reason,
                'created_by'         => $createdBy,
                'approved_by'        => $approvedBy,
            ]);
        });
    }

    /**
     * Deduct VL or SL for an approved leave application. Only call this
     * for a recognized, single leave type ('VL' or 'SL') — the caller is
     * responsible for deciding whether a given application's leave_types
     * value maps cleanly to one of these before calling.
     *
     * Throws if it would take the balance negative — there is no SL/VL/
     * LWOP cascade yet (spec Sections 15-17), so an insufficient balance
     * is a hard stop for now, not a silent partial deduction.
     */
    public function deductForLeave(
        int $employeeId,
        string $leaveType, // 'VL' or 'SL'
        float $days,
        string $effectiveDate,
        int $referenceId,
        ?int $createdBy = null,
        ?int $approvedBy = null
    ): LeaveCreditTransaction {
        if (! in_array($leaveType, ['VL', 'SL'], true)) {
            throw new RuntimeException("deductForLeave() called with unrecognized leave type: {$leaveType}");
        }

        if ($days <= 0) {
            throw new RuntimeException('Deduction days must be greater than zero.');
        }

        return DB::transaction(function () use ($employeeId, $leaveType, $days, $effectiveDate, $referenceId, $createdBy, $approvedBy) {

            $balance = LeaveBalance::where('employee_id', $employeeId)
                ->lockForUpdate()
                ->first();

            if (! $balance) {
                throw new RuntimeException(
                    "No leave balance exists for employee {$employeeId}. ".
                    'An opening balance must be recorded before leave can be deducted automatically.'
                );
            }

            $vlBefore = (float) $balance->vl_balance;
            $slBefore = (float) $balance->sl_balance;

            $vlDelta = $leaveType === 'VL' ? -$days : 0;
            $slDelta = $leaveType === 'SL' ? -$days : 0;

            $vlAfter = $vlBefore + $vlDelta;
            $slAfter = $slBefore + $slDelta;

            if ($vlAfter < 0 || $slAfter < 0) {
                throw new RuntimeException(
                    "This would take the employee's {$leaveType} balance negative ".
                    "({$days} days requested, insufficient balance available). ".
                    'No SL/VL/LWOP cascade is implemented yet — resolve manually before approving.'
                );
            }

            $balance->update([
                'vl_balance' => $vlAfter,
                'sl_balance' => $slAfter,
                'updated_by' => $createdBy,
            ]);

            return LeaveCreditTransaction::create([
                'employee_id'        => $employeeId,
                'transaction_type'   => LeaveCreditTransaction::TYPE_LEAVE_DEDUCTION,
                'vl_amount'          => $vlDelta,
                'sl_amount'          => $slDelta,
                'vl_balance_before'  => $vlBefore,
                'sl_balance_before'  => $slBefore,
                'vl_balance_after'   => $vlAfter,
                'sl_balance_after'   => $slAfter,
                'reference_type'     => 'Leave',
                'reference_id'       => $referenceId,
                'effective_date'     => $effectiveDate,
                'description'        => "Deduction for approved {$leaveType} application (#{$referenceId})",
                'created_by'         => $createdBy,
                'approved_by'        => $approvedBy,
            ]);
        });
    }

    /**
     * Record automatic monthly VL/SL earning for an employee. Requires an
     * existing balance (opening balance must already be recorded) — this
     * method only adds to an existing balance, it never creates one.
     */
    public function recordMonthlyEarning(
        int $employeeId,
        float $vlEarned,
        float $slEarned,
        string $effectiveDate,
        ?int $createdBy = null
    ): LeaveCreditTransaction {
        return DB::transaction(function () use ($employeeId, $vlEarned, $slEarned, $effectiveDate, $createdBy) {

            $balance = LeaveBalance::where('employee_id', $employeeId)
                ->lockForUpdate()
                ->first();

            if (! $balance) {
                throw new RuntimeException(
                    "No leave balance exists for employee {$employeeId}. ".
                    'An opening balance must be recorded before monthly earning can be applied.'
                );
            }

            $vlBefore = (float) $balance->vl_balance;
            $slBefore = (float) $balance->sl_balance;
            $vlAfter  = $vlBefore + $vlEarned;
            $slAfter  = $slBefore + $slEarned;

            $balance->update([
                'vl_balance' => $vlAfter,
                'sl_balance' => $slAfter,
                'as_of_date' => $effectiveDate,
                'updated_by' => $createdBy,
            ]);

            return LeaveCreditTransaction::create([
                'employee_id'        => $employeeId,
                'transaction_type'   => LeaveCreditTransaction::TYPE_MONTHLY_EARNING,
                'vl_amount'          => $vlEarned,
                'sl_amount'          => $slEarned,
                'vl_balance_before'  => $vlBefore,
                'sl_balance_before'  => $slBefore,
                'vl_balance_after'   => $vlAfter,
                'sl_balance_after'   => $slAfter,
                'reference_type'     => 'MonthlyLeaveCredit',
                'reference_id'       => null,
                'effective_date'     => $effectiveDate,
                'description'        => 'Monthly VL/SL earning (ORL Sec. 27)',
                'created_by'         => $createdBy,
                'approved_by'        => null,
            ]);
        });
    }

    /**
     * Read-only convenience — current balance for an employee, or null if
     * they don't have one yet (e.g. opening balance not entered).
     */
    public function getBalance(int $employeeId): ?LeaveBalance
    {
        return LeaveBalance::where('employee_id', $employeeId)->first();
    }
}
