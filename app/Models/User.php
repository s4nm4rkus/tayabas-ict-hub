<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    // Standard auto-increment integer primary key
    public $incrementing = true;
    protected $keyType = 'int';
    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',       // ICTHUB-2025-0001 format
        'username',
        'password',
        'user_pos',
        'user_stat',
        'pass_change',
        'otp',
        'otp_expires_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'otp',
    ];

    protected $casts = [
        'pass_change'    => 'boolean',
        'otp_expires_at' => 'datetime',
    ];

    // Relationships
    public function employee()
    {
        return $this->hasOne(Employee::class, 'user_id', 'id');
    }

    public function board()
    {
        return $this->hasMany(Board::class, 'user_id', 'id');
    }

    public function auditTrail()
    {
        return $this->hasMany(AuditTrail::class, 'user_id', 'id');
    }

    public function fingerPrint()
    {
        return $this->hasOne(FingerPrint::class, 'user_id', 'id');
    }
}