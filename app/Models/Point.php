<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Point extends Model
{
    protected $table = 'tbl_points';

    protected $fillable = [
        'userid',
        't_date',
        'acc_points',
    ];

    protected $casts = [
        't_date' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'userid', 'id');
    }
}
