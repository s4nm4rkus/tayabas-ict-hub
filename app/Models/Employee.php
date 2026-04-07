<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $table = 'tbl_employee_info';
    protected $primaryKey = 'user_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'user_id',
        'last_name',
        'first_name',
        'middle_name',
        'ex_name',
        'gender',
        'birthdate',
        'place_of_birth',
        'contact_num',
        'gov_email',
        'employee_no',
        'philhealth',
        'pagibig',
        'TIN',
        'street',
        'street_brgy',
        'municipality',
        'province',
        'region',
        'disability',
        'photo_path',
        'bp_no',
        'date_encoded',
    ];

    protected $casts = [
        'birthdate' => 'date',
        'date_encoded' => 'date',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function employment()
    {
        return $this->hasOne(EmploymentInfo::class, 'user_id', 'user_id');
    }

    public function education()
    {
        return $this->hasOne(EducationalBg::class, 'user_id', 'user_id');
    }

    public function eligibility()
    {
        return $this->hasOne(Eligibility::class, 'user_id', 'user_id');
    }

    public function leaves()
    {
        return $this->hasMany(Leave::class, 'user_id', 'user_id');
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class, 'user_id', 'user_id');
    }

    public function points()
    {
        return $this->hasMany(Point::class, 'userid', 'user_id');
    }

    public function serviceRecords()
    {
        return $this->hasMany(ServiceRecord::class, 'user_id', 'user_id');
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class, 'user_id', 'user_id');
    }

    public function certRequests()
    {
        return $this->hasMany(CertRequest::class, 'user_id', 'user_id');
    }

    public function fingerPrint()
    {
        return $this->hasOne(FingerPrint::class, 'user_id', 'user_id');
    }

    // Full name accessor
    public function getFullNameAttribute()
    {
        return trim("{$this->first_name} {$this->middle_name} {$this->last_name}");
    }
}