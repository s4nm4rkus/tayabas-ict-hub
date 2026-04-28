<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmploymentInfo extends Model
{
    protected $table = 'tbl_employment_info';

    protected $primaryKey = 'user_id';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'user_id',
        'position',
        'sub_position',
        'date_orig_appoint',
        'salary_grade',
        'salary_step',
        'salary_effect_date',
        'vice',
        'vice_reason',
        'nature_appoint',
        'status_appoint',
        'plantilla_item_no',
        'plantilla_inclu',
        'school_office_assign',
        'school_detailed_office_assign',
        'designated_from',
        'designated_to',
        'separation',
        'separation_date',
        'head',
    ];

    protected $casts = [
        'date_orig_appoint' => 'date',
        'salary_effect_date' => 'date',
        'designated_from' => 'date',
        'designated_to' => 'date',
        'separation_date' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'user_id', 'id');
    }
}
