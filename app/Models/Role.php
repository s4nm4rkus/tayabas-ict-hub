<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'tbl_role';

    protected $primaryKey = 'role_id';

    protected $fillable = [
        'role_desc',
        'role_cat',
        'role_type',
        'role_head',
    ];
}
