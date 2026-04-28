<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = 'tbl_messages';

    protected $fillable = [
        'userid',
        'receiver',
        'messages',
        'subject_mes',
        'date_time',
        'mes_status',
    ];

    protected $casts = [
        'date_time' => 'datetime',
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'userid', 'id');
    }
}
