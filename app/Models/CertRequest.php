<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CertRequest extends Model
{
    protected $table = 'tbl_request';

    protected $fillable = [
        'user_id',
        'req_type',
        'date_req',
        'time_req',
        'approve_date',
        'approve_time',
        'req_status',
        'approve_by',
    ];

    protected $casts = [
        'date_req' => 'date',
        'approve_date' => 'date',
    ];

   public function employee()
    {
        return $this->belongsTo(Employee::class, 'user_id', 'id');
    }
   public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approve_by', 'id');
    }
}