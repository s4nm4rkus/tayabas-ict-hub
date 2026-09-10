<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveBalance extends Model
{
    protected $table = 'tbl_leave_balances';

    protected $fillable = [
        'employee_id',
        'vl_balance',
        'sl_balance',
        'as_of_date',
        'updated_by',
    ];

    protected $casts = [
        'vl_balance' => 'decimal:3',
        'sl_balance' => 'decimal:3',
        'as_of_date' => 'date',
    ];

    // ── Relationships ──────────────────────────────────────────────────

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    // ── Read-only helpers ──────────────────────────────────────────────
    // Deliberately no setVlBalance()/save-style mutators here. This model
    // is a cache of the ledger (tbl_leave_credit_transactions) — nothing
    // should write to it directly except LeaveCreditService, so that the
    // ledger and this snapshot can never silently drift apart. Any direct
    // ->update() call on this model outside that service is a red flag.

    public function total(): float
    {
        return (float) $this->vl_balance + (float) $this->sl_balance;
    }
}