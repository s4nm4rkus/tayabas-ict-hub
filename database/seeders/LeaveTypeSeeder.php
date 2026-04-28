<?php

namespace Database\Seeders;

use App\Models\LeaveType;
use Illuminate\Database\Seeder;

class LeaveTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            'Vacation Leave',
            'Sick Leave',
            'Maternity Leave',
            'Paternity Leave',
            'Special Leave Benefits',
            'Force Leave',
            'Emergency Leave',
            'Study Leave',
            'VAWC Leave',
            'Solo Parent Leave',
        ];

        foreach ($types as $type) {
            LeaveType::firstOrCreate(['leavetype' => $type]);
        }
    }
}
