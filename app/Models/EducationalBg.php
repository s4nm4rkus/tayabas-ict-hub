<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EducationalBg extends Model
{
    protected $table = 'tbl_educational_bg';

    protected $primaryKey = 'user_id';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'user_id',
        'elementary',
        'elem_duration',
        'secondary',
        'second_duration',
        'college',
        'college_school',
        'college_duration',
        'vocational',
        'voc_school',
        'voca_duration',
        'masters_degree',
        'master_duration',
        'master_units',
        'doc_degree',
        'doc_duration',
        'doc_units',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'user_id', 'id');
    }
}
