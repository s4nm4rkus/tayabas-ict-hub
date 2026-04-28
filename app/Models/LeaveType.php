<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{
    protected $table = 'tbl_leavetype';

    protected $fillable = [
        'leavetype',
    ];
}
