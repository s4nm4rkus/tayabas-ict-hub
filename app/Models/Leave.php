<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    protected $table = 'tbl_leave';

    protected $fillable = [
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
    ];

    protected $casts = [
        'date_applied' => 'date',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'user_id', 'id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approve_by', 'id');
    }

    public function deptHead()
    {
        return $this->belongsTo(User::class, 'dept_head', 'id');
    }
}
