<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonthlyLeaveCredit extends Model
{
    protected $table = 'tbl_monthly_leave_credits';

    protected $fillable = [
        'employee_id',
        'year',
        'month',
        'actual_service_days',
        'lwop_days',
        'vl_earned',
        'sl_earned',
        'computation_source',
        'computed_by',
        'computed_at',
    ];

    protected $casts = [
        'actual_service_days' => 'decimal:2',
        'lwop_days'           => 'decimal:2',
        'vl_earned'           => 'decimal:3',
        'sl_earned'           => 'decimal:3',
        'computed_at'         => 'datetime',
    ];

    // ── Relationships ──────────────────────────────────────────────────

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

    public function computedBy()
    {
        return $this->belongsTo(User::class, 'computed_by', 'id');
    }

    // ── Query Scopes ───────────────────────────────────────────────────

    public function scopeForPeriod($query, int $year, int $month)
    {
        return $query->where('year', $year)->where('month', $month);
    }

    public function scopeForEmployee($query, int $employeeId)
    {
        return $query->where('employee_id', $employeeId);
    }
}