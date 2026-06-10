<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceLog extends Model
{
    protected $table = 'attendance_logs';

    protected $fillable = [
        'emp_code',
        'punch_time',
        'punch_state',
        'verify_type',
        'source_file',
    ];

    protected $casts = [
        'punch_time' => 'datetime',
    ];
}
