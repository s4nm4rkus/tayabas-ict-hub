<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmploymentHistory extends Model
{
    protected $table = 'tbl_employment_history';

    protected $fillable = [
        'user_id',
        'position',
        'sub_position',
        'salary_grade',
        'salary_step',
        'nature_appoint',
        'status_appoint',
        'station',
        'effective_date',
        'end_date',
        'change_reason',
        'step_anchor',
        'created_by',
    ];

    protected $casts = [
        'effective_date' => 'date',
        'end_date'       => 'date',
        'step_anchor'    => 'date',
        'salary_grade'   => 'integer',
        'salary_step'    => 'integer',
    ];

    // ── Relationships ─────────────────────────────────────────────────────

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'user_id', 'user_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    // ── Helpers ───────────────────────────────────────────────────────────

    public function isActive(): bool
    {
        return is_null($this->end_date);
    }

    public function isCurrent(): bool
    {
        return $this->isActive();
    }
}
