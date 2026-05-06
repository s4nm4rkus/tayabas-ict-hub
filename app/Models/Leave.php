<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    protected $table = 'tbl_leave';

    protected $fillable = [
        // ── Existing Core Fields ───────────────────────────────────────────
        'user_id',
        'fullname',
        'position',
        'leavetype',
        'date_applied',
        'start_date',
        'end_date',
        'total_days',
        'attachments',
        'leave_status',
        'remarks',
        'dept_head',
        'approve_by',
        'leavefile',

        // ── Form 6 Fields ──────────────────────────────────────────────────
        'department',
        'salary',
        'leave_types',
        'leave_details',
        'commutation',
        'number_of_days',
        'inclusive_dates',

        // ── HR Leave Credits (Section 7A) ──────────────────────────────────
        'hr_as_of',
        'vl_earned',
        'vl_less',
        'vl_balance',
        'sl_earned',
        'sl_less',
        'sl_balance',

        // ── AO Step (Section 7B) ───────────────────────────────────────────
        'ao_id',
        'ao_action',
        'ao_at',
        'ao_remarks',

        // ── ASDS Step (Section 7C / 7D) ────────────────────────────────────
        'asds_id',
        'asds_action',
        'asds_at',
        'asds_days_with_pay',
        'asds_days_without_pay',
        'asds_others',
        'asds_disapproval',

        // ── E-Signature Watermark Names ────────────────────────────────────
        'head_esign_name',
        'hr_esign_name',
        'ao_esign_name',
        'asds_esign_name',
        'employee_esign_path',
        'head_esign_path',
        'hr_esign_path',
        'ao_esign_path',
        'asds_esign_path',
    ];

    protected $casts = [
        'date_applied' => 'date',
        'start_date'   => 'date',
        'end_date'     => 'date',
        'ao_at'        => 'datetime',
        'asds_at'      => 'datetime',
    ];

    // ── Relationships ──────────────────────────────────────────────────────

    // Employee who filed the leave
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'user_id', 'id');
    }

    // HR approver (existing)
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approve_by', 'id');
    }

    // Department Head (existing)
    public function deptHead()
    {
        return $this->belongsTo(User::class, 'dept_head', 'id');
    }

    // AO approver (new)
    public function aoApprover()
    {
        return $this->belongsTo(User::class, 'ao_id', 'id');
    }

    // ASDS approver (new)
    public function asdsApprover()
    {
        return $this->belongsTo(User::class, 'asds_id', 'id');
    }

    // ── Helpers ────────────────────────────────────────────────────────────

    /**
     * Returns true if the leave is still cancellable by the employee.
     */
    public function isCancellable(): bool
    {
        return in_array($this->leave_status, ['Pending Head', 'Pending HR']);
    }

    /**
     * Human-readable step label for progress tracker UI.
     */
    public function currentStepLabel(): string
    {
        return match ($this->leave_status) {
            'Pending Head' => 'Waiting for Head endorsement',
            'Pending HR'   => 'Waiting for HR approval',
            'Pending AO'   => 'Waiting for Administrative Officer',
            'Pending ASDS' => 'Waiting for ASDS final approval',
            'Approved'     => 'Fully Approved',
            'Declined'     => 'Declined',
            'Cancelled'    => 'Cancelled',
            default        => $this->leave_status,
        };
    }

    /**
     * Returns step number 1–5 for the progress tracker UI.
     * 0 = Declined or Cancelled
     */
    public function currentStep(): int
    {
        return match ($this->leave_status) {
            'Pending Head' => 1,
            'Pending HR'   => 2,
            'Pending AO'   => 3,
            'Pending ASDS' => 4,
            'Approved'     => 5,
            default        => 0,
        };
    }
}
