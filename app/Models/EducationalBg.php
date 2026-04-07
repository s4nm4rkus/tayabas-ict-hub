<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EducationalBg extends Model
{
    protected $table = 'tbl_educational_bg';
    protected $primaryKey = 'user_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'user_id',
        'elementary',
        'elem_duration',
        'secondary',
        'second_duration',
        'college',
        'college_duration',
        'college_school',
        'vocational',
        'voca_duration',
        'voc_school',
        'masters_degree',
        'master_duration',
        'master_units',
        'doc_degree',
        'doc_duration',
        'doc_units',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'user_id', 'user_id');
    }
}