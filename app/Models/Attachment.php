<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    protected $table = 'tbl_attachments';

    protected $fillable = [
        'user_id',
        'title',
        'conduct_by',
        'date_from',
        'date_to',
        'file_path',
    ];

    protected $casts = [
        'date_from' => 'date',
        'date_to' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'user_id', 'id');
    }
}
