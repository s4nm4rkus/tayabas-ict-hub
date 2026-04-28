<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeavePoint extends Model
{
    protected $table = 'tbl_leavepoints';

    protected $fillable = [
        'leave_day',
        'point_equi',
        'month',
        'leave_earn',
        'vacation_leave',
        'leave_earn_wop',
    ];
}
