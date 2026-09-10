<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveCreditTransaction extends Model
{
    protected $table = 'tbl_leave_credit_transactions';

    // Transaction type constants — used instead of magic strings elsewhere
    // in the codebase. Add new types here as they're needed; the column
    // itself is a plain string so no migration is required to add one.
    public const TYPE_OPENING_BALANCE     = 'OPENING_BALANCE';
    public const TYPE_MONTHLY_EARNING     = 'MONTHLY_EARNING';
    public const TYPE_LEAVE_DEDUCTION     = 'LEAVE_DEDUCTION';
    public const TYPE_LWOP                = 'LWOP';
    public const TYPE_TARDINESS_DEDUCTION = 'TARDINESS_DEDUCTION';
    public const TYPE_MANUAL_ADJUSTMENT   = 'MANUAL_ADJUSTMENT';
    public const TYPE_TRANSFER_IN         = 'TRANSFER_IN';
    public const TYPE_TRANSFER_OUT        = 'TRANSFER_OUT';
    public const TYPE_MONETIZATION        = 'MONETIZATION';
    public const TYPE_TERMINAL_LEAVE      = 'TERMINAL_LEAVE';
    public const TYPE_FORFEITURE          = 'FORFEITURE';
    public const TYPE_CORRECTION          = 'CORRECTION';

    protected $fillable = [
        'employee_id',
        'transaction_type',
        'vl_amount',
        'sl_amount',
        'vl_balance_before',
        'sl_balance_before',
        'vl_balance_after',
        'sl_balance_after',
        'reference_type',
        'reference_id',
        'effective_date',
        'description',
        'created_by',
        'approved_by',
    ];

    protected $casts = [
        'vl_amount'         => 'decimal:3',
        'sl_amount'         => 'decimal:3',
        'vl_balance_before' => 'decimal:3',
        'sl_balance_before' => 'decimal:3',
        'vl_balance_after'  => 'decimal:3',
        'sl_balance_after'  => 'decimal:3',
        'effective_date'    => 'date',
    ];

    // ── Relationships ──────────────────────────────────────────────────

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by', 'id');
    }

    // Not a standard Eloquent morphTo — reference_type/reference_id are
    // plain descriptive columns (e.g. 'Leave', tbl_leave.id), not backed
    // by a registered morph map. This is a manual, read-only lookup rather
    // than a queryable relation, since the target table varies by
    // transaction_type and some types (e.g. MANUAL_ADJUSTMENT) may have
    // no reference at all.
    public function resolveReference(): ?Model
    {
        if (! $this->reference_type || ! $this->reference_id) {
            return null;
        }

        return match ($this->reference_type) {
            'Leave' => Leave::find($this->reference_id),
            default => null,
        };
    }

    // ── Query Scopes ───────────────────────────────────────────────────

    public function scopeForEmployee($query, int $employeeId)
    {
        return $query->where('employee_id', $employeeId);
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('transaction_type', $type);
    }
}