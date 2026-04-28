<?php

namespace Database\Seeders;

use App\Models\SubPosition;
use Illuminate\Database\Seeder;

class SubPositionSeeder extends Seeder
{
    public function run(): void
    {
        $subPositions = [
            // Teaching designations
            ['main_pos' => 'Teacher I',        'sub_position' => 'Class Adviser'],
            ['main_pos' => 'Teacher I',        'sub_position' => 'Subject Teacher'],
            ['main_pos' => 'Teacher II',       'sub_position' => 'Class Adviser'],
            ['main_pos' => 'Teacher II',       'sub_position' => 'Subject Coordinator'],
            ['main_pos' => 'Teacher III',      'sub_position' => 'Class Adviser'],
            ['main_pos' => 'Teacher III',      'sub_position' => 'Department Coordinator'],
            ['main_pos' => 'Master Teacher I', 'sub_position' => 'Grade Level Chairman'],
            ['main_pos' => 'Master Teacher I', 'sub_position' => 'Department Head'],
            ['main_pos' => 'Master Teacher II', 'sub_position' => 'Grade Level Chairman'],
            ['main_pos' => 'Master Teacher II', 'sub_position' => 'OIC Principal'],
            ['main_pos' => 'Head Teacher',     'sub_position' => 'Grade Level Chairman'],
            ['main_pos' => 'Head Teacher',     'sub_position' => 'Department Head'],

            // Administrative designations
            ['main_pos' => 'Administrative Officer', 'sub_position' => 'OIC'],
            ['main_pos' => 'Administrative Officer', 'sub_position' => 'Budget Officer'],
            ['main_pos' => 'Administrative Officer', 'sub_position' => 'Property Custodian'],
            ['main_pos' => 'Administrative Aide',    'sub_position' => 'Clerk'],
            ['main_pos' => 'Administrative Aide',    'sub_position' => 'Utility Worker'],
            ['main_pos' => 'Bookkeeper',             'sub_position' => 'Cashier'],
            ['main_pos' => 'Bookkeeper',             'sub_position' => 'Disbursing Officer'],

            // Principal designations
            ['main_pos' => 'School Principal',    'sub_position' => 'School Head'],
            ['main_pos' => 'School Principal',    'sub_position' => 'OIC Principal'],
            ['main_pos' => 'Assistant Principal', 'sub_position' => 'OIC Principal'],
            ['main_pos' => 'Assistant Principal', 'sub_position' => 'Assistant School Head'],
        ];

        foreach ($subPositions as $sub) {
            SubPosition::firstOrCreate(
                [
                    'main_pos' => $sub['main_pos'],
                    'sub_position' => $sub['sub_position'],
                ],
                $sub
            );
        }
    }
}
