<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FingerPrint extends Model
{
    protected $table = 'finger_print';

    protected $fillable = [
        'user_id',
        'finger_print',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}