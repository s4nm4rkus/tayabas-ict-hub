<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $table = 'tbl_attendance';

    protected $fillable = [
        'user_id',
        'fullname',
        'position',
        't_date',
        'am_time_in',
        'am_time_out',
        'pm_time_in',
        'pm_time_out',
        'total_hours',
        'late_minutes',        // ← was missing — caused silent 0 on every save
        'undertime_minutes',   // ← was missing — caused silent 0 on every save
        'import_source',       // ← was missing
    ];

    protected $casts = [
        't_date' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'user_id', 'id');
    }
}
