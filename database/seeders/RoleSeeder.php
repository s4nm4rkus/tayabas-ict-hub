<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            // Super Admin
            ['role_desc' => 'Super Administrator', 'role_cat' => 'Non-Teaching', 'role_type' => 'Employee',        'role_head' => null],

            // HR
            ['role_desc' => 'HR',                  'role_cat' => 'Non-Teaching', 'role_type' => 'Employee',        'role_head' => null],

            // Department Heads
            ['role_desc' => 'School Principal',    'role_cat' => 'Teaching',     'role_type' => 'Department Head', 'role_head' => null],
            ['role_desc' => 'Assistant Principal', 'role_cat' => 'Teaching',     'role_type' => 'Department Head', 'role_head' => null],
            ['role_desc' => 'Head Teacher',        'role_cat' => 'Teaching',     'role_type' => 'Department Head', 'role_head' => null],

            // Teaching
            ['role_desc' => 'Teacher I',           'role_cat' => 'Teaching',     'role_type' => 'Employee',        'role_head' => null],
            ['role_desc' => 'Teacher II',          'role_cat' => 'Teaching',     'role_type' => 'Employee',        'role_head' => null],
            ['role_desc' => 'Teacher III',         'role_cat' => 'Teaching',     'role_type' => 'Employee',        'role_head' => null],
            ['role_desc' => 'Master Teacher I',    'role_cat' => 'Teaching',     'role_type' => 'Employee',        'role_head' => null],
            ['role_desc' => 'Master Teacher II',   'role_cat' => 'Teaching',     'role_type' => 'Employee',        'role_head' => null],

            // Non-Teaching
            ['role_desc' => 'Administrative Officer',  'role_cat' => 'Non-Teaching', 'role_type' => 'Employee', 'role_head' => null],
            ['role_desc' => 'Administrative Aide',     'role_cat' => 'Non-Teaching', 'role_type' => 'Employee', 'role_head' => null],
            ['role_desc' => 'Bookkeeper',              'role_cat' => 'Non-Teaching', 'role_type' => 'Employee', 'role_head' => null],
            ['role_desc' => 'Utility Worker',          'role_cat' => 'Non-Teaching', 'role_type' => 'Employee', 'role_head' => null],
            ['role_desc' => 'Security Guard',          'role_cat' => 'Non-Teaching', 'role_type' => 'Employee', 'role_head' => null],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['role_desc' => $role['role_desc']],
                $role
            );
        }
    }
}