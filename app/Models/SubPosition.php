<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubPosition extends Model
{
    protected $table = 'sub_position';

    protected $fillable = [
        'main_pos',
        'sub_position',
        'idunno',
    ];
}
