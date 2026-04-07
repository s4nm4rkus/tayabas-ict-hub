<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceRecord extends Model
{
    protected $table = 'tbl_service_rec';

    protected $fillable = [
        'user_id',
        'inclu_from',
        'inclu_to',
        'designation',
        'service_status',
        'salary_step',
        'salary_grade',
        'station',
        'branch',
        'separation',
        'position',
    ];

    protected $casts = [
        'inclu_from' => 'date',
        'inclu_to' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'user_id', 'user_id');
    }
}