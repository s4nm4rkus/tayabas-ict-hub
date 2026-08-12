<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            // Super Admin
            ['role_desc' => 'Super Administrator', 'role_cat' => 'Non-Teaching', 'role_type' => 'Employee',        'role_head' => null],

            // HR
            ['role_desc' => 'HR',                  'role_cat' => 'Non-Teaching', 'role_type' => 'Employee',        'role_head' => null],

            // ── NEW: AO and ASDS for Form 6 approval flow ─────────────────
            // AO already exists as 'Administrative Officer' position
            // ASDS is a new top-level approver
            ['role_desc' => 'ASDS',                'role_cat' => 'Non-Teaching', 'role_type' => 'Employee',        'role_head' => null],

            // ⚠️ Possible duplicate of ASDS above — confirm before keeping both
            // ['role_desc' => 'Assistant Schools Division Superintendent', 'role_cat' => 'Non-Teaching', 'role_type' => 'Employee', 'role_head' => null],

            // Department Heads
            ['role_desc' => 'School Principal',    'role_cat' => 'Teaching',     'role_type' => 'Department Head', 'role_head' => null],
            ['role_desc' => 'Assistant Principal', 'role_cat' => 'Teaching',     'role_type' => 'Department Head', 'role_head' => null],
            ['role_desc' => 'Head Teacher',        'role_cat' => 'Teaching',     'role_type' => 'Department Head', 'role_head' => null],

            // NEW: Numbered Principal / Head Teacher variants found in staffing data
            ['role_desc' => 'School Principal I',        'role_cat' => 'Teaching', 'role_type' => 'Department Head', 'role_head' => null],
            ['role_desc' => 'School Principal II',       'role_cat' => 'Teaching', 'role_type' => 'Department Head', 'role_head' => null],
            ['role_desc' => 'School Principal III',      'role_cat' => 'Teaching', 'role_type' => 'Department Head', 'role_head' => null],
            ['role_desc' => 'School Principal IV',       'role_cat' => 'Teaching', 'role_type' => 'Department Head', 'role_head' => null],
            ['role_desc' => 'Assistant School Principal II', 'role_cat' => 'Teaching', 'role_type' => 'Department Head', 'role_head' => null],
            ['role_desc' => 'Head Teacher I',            'role_cat' => 'Teaching', 'role_type' => 'Department Head', 'role_head' => null],
            ['role_desc' => 'Head Teacher III',          'role_cat' => 'Teaching', 'role_type' => 'Department Head', 'role_head' => null],

            // Teaching
            ['role_desc' => 'Teacher I',           'role_cat' => 'Teaching',     'role_type' => 'Employee',        'role_head' => null],
            ['role_desc' => 'Teacher II',          'role_cat' => 'Teaching',     'role_type' => 'Employee',        'role_head' => null],
            ['role_desc' => 'Teacher III',         'role_cat' => 'Teaching',     'role_type' => 'Employee',        'role_head' => null],
            ['role_desc' => 'Master Teacher I',    'role_cat' => 'Teaching',     'role_type' => 'Employee',        'role_head' => null],
            ['role_desc' => 'Master Teacher II',   'role_cat' => 'Teaching',     'role_type' => 'Employee',        'role_head' => null],

            // NEW: Additional teaching positions found in staffing data
            ['role_desc' => 'Teacher V',                     'role_cat' => 'Teaching', 'role_type' => 'Employee', 'role_head' => null],
            ['role_desc' => 'Teacher VI',                    'role_cat' => 'Teaching', 'role_type' => 'Employee', 'role_head' => null],
            ['role_desc' => 'Teacher I (ALS)',               'role_cat' => 'Teaching', 'role_type' => 'Employee', 'role_head' => null],
            ['role_desc' => 'Teacher II (ALS)',              'role_cat' => 'Teaching', 'role_type' => 'Employee', 'role_head' => null],
            ['role_desc' => 'Special Science Teacher I',     'role_cat' => 'Teaching', 'role_type' => 'Employee', 'role_head' => null],
            ['role_desc' => 'Special Education Teacher I',   'role_cat' => 'Teaching', 'role_type' => 'Employee', 'role_head' => null],
            ['role_desc' => 'Special Education Teacher II',  'role_cat' => 'Teaching', 'role_type' => 'Employee', 'role_head' => null],

            // Non-Teaching
            ['role_desc' => 'Administrative Officer',  'role_cat' => 'Non-Teaching', 'role_type' => 'Employee', 'role_head' => null],
            ['role_desc' => 'Administrative Aide',     'role_cat' => 'Non-Teaching', 'role_type' => 'Employee', 'role_head' => null],
            ['role_desc' => 'Bookkeeper',              'role_cat' => 'Non-Teaching', 'role_type' => 'Employee', 'role_head' => null],
            ['role_desc' => 'Utility Worker',          'role_cat' => 'Non-Teaching', 'role_type' => 'Employee', 'role_head' => null],
            ['role_desc' => 'Security Guard',          'role_cat' => 'Non-Teaching', 'role_type' => 'Employee', 'role_head' => null],

            // NEW: Additional non-teaching positions found in staffing data
            ['role_desc' => 'Administrative Officer II',                  'role_cat' => 'Non-Teaching', 'role_type' => 'Employee', 'role_head' => null],
            ['role_desc' => 'Administrative Officer IV',                  'role_cat' => 'Non-Teaching', 'role_type' => 'Employee', 'role_head' => null],
            ['role_desc' => 'Administrative Officer V',                   'role_cat' => 'Non-Teaching', 'role_type' => 'Employee', 'role_head' => null],
            ['role_desc' => 'Administrative Assistant II',                'role_cat' => 'Non-Teaching', 'role_type' => 'Employee', 'role_head' => null],
            ['role_desc' => 'Admin Assistant I',                          'role_cat' => 'Non-Teaching', 'role_type' => 'Employee', 'role_head' => null],
            ['role_desc' => 'Admin Assistant II',                         'role_cat' => 'Non-Teaching', 'role_type' => 'Employee', 'role_head' => null],
            ['role_desc' => 'Admin Assistant III',                        'role_cat' => 'Non-Teaching', 'role_type' => 'Employee', 'role_head' => null],
            ['role_desc' => 'Administrative Aide I (Utility Worker I)',   'role_cat' => 'Non-Teaching', 'role_type' => 'Employee', 'role_head' => null],
            ['role_desc' => 'Administrative Aide IV',                     'role_cat' => 'Non-Teaching', 'role_type' => 'Employee', 'role_head' => null],
            ['role_desc' => 'Administrative Aide VI',                     'role_cat' => 'Non-Teaching', 'role_type' => 'Employee', 'role_head' => null],
            ['role_desc' => 'Project Development Officer I',              'role_cat' => 'Non-Teaching', 'role_type' => 'Employee', 'role_head' => null],
            ['role_desc' => 'Project Development Officer II',             'role_cat' => 'Non-Teaching', 'role_type' => 'Employee', 'role_head' => null],
            ['role_desc' => 'Education Program Specialist II',            'role_cat' => 'Non-Teaching', 'role_type' => 'Employee', 'role_head' => null],
            ['role_desc' => 'Education Program Supervisor',               'role_cat' => 'Non-Teaching', 'role_type' => 'Employee', 'role_head' => null],
            ['role_desc' => 'Senior Education Program Specialist',        'role_cat' => 'Non-Teaching', 'role_type' => 'Employee', 'role_head' => null],
            ['role_desc' => 'Chief Education Supervisor',                 'role_cat' => 'Non-Teaching', 'role_type' => 'Employee', 'role_head' => null],
            ['role_desc' => 'Registrar I',                                'role_cat' => 'Non-Teaching', 'role_type' => 'Employee', 'role_head' => null],
            ['role_desc' => 'Nurse II',                                   'role_cat' => 'Non-Teaching', 'role_type' => 'Employee', 'role_head' => null],
            ['role_desc' => 'Dentist II',                                 'role_cat' => 'Non-Teaching', 'role_type' => 'Employee', 'role_head' => null],
            ['role_desc' => 'Medical Officer III',                        'role_cat' => 'Non-Teaching', 'role_type' => 'Employee', 'role_head' => null],
            ['role_desc' => 'Librarian II',                               'role_cat' => 'Non-Teaching', 'role_type' => 'Employee', 'role_head' => null],
            ['role_desc' => 'Planning Officer III',                       'role_cat' => 'Non-Teaching', 'role_type' => 'Employee', 'role_head' => null],
            ['role_desc' => 'Accountant III',                             'role_cat' => 'Non-Teaching', 'role_type' => 'Employee', 'role_head' => null],
            ['role_desc' => 'Information Technology Officer I',           'role_cat' => 'Non-Teaching', 'role_type' => 'Employee', 'role_head' => null],
            ['role_desc' => 'Attorney III',                               'role_cat' => 'Non-Teaching', 'role_type' => 'Employee', 'role_head' => null],
            ['role_desc' => 'Legal Assistant I',                          'role_cat' => 'Non-Teaching', 'role_type' => 'Employee', 'role_head' => null],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['role_desc' => $role['role_desc']],
                $role
            );
        }
    }
}
