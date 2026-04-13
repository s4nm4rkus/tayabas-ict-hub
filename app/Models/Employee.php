<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\EmploymentInfo;
use App\Models\EducationalBg;
use App\Models\Eligibility;
use App\Models\ServiceRecord;
use App\Models\Attachment;
use App\Models\Leave;
use App\Models\User;

class Employee extends Model
{
    protected $table = 'tbl_employee_info';
    protected $casts = [
        'birthdate' => 'date',
        'date_encoded' => 'datetime',
    ];
    protected $fillable = [
        'user_id',        // integer FK to users.id
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

    // Computed full name
    public function getFullNameAttribute(): string
    {
        return trim("{$this->last_name}, {$this->first_name} " . ($this->middle_name ?? ''));
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

        public function employment()
    {
        return $this->hasOne(EmploymentInfo::class, 'user_id', 'id');
    }

    public function education()
    {
        return $this->hasOne(EducationalBg::class, 'user_id', 'id');
    }

    public function eligibility()
    {
        return $this->hasOne(Eligibility::class, 'user_id', 'id');
    }

    public function leaves()
    {
        return $this->hasMany(Leave::class, 'user_id', 'id');
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class, 'user_id', 'id');
    }

    public function points()
    {
        return $this->hasMany(Point::class, 'userid', 'id');
    }

    public function serviceRecords()
    {
        return $this->hasMany(ServiceRecord::class, 'user_id', 'id');
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class, 'user_id', 'id');
    }

    public function certRequests()
    {
        return $this->hasMany(CertRequest::class, 'user_id', 'id');
    }

    public function fingerPrint()
    {
        return $this->hasOne(FingerPrint::class, 'user_id', 'id');
    }
}