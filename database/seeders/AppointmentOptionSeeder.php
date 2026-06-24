<?php

namespace Database\Seeders;

use App\Models\AppointmentOption;
use Illuminate\Database\Seeder;

class AppointmentOptionSeeder extends Seeder
{
    public function run(): void
    {
        // ── Status of Appointment (CSC / DepEd standard) ──────────────────
        $statusOptions = [
            ['value' => 'Permanent',           'label' => 'Permanent',                     'sort' => 1],
            ['value' => 'Provisional',         'label' => 'Provisional',                   'sort' => 2],
            ['value' => 'Substitute',          'label' => 'Substitute',                    'sort' => 3],

        ];

        foreach ($statusOptions as $opt) {
            AppointmentOption::updateOrCreate(
                ['option_type' => 'status_appoint', 'option_value' => $opt['value']],
                ['option_label' => $opt['label'], 'sort_order' => $opt['sort'], 'is_active' => true]
            );
        }

        // ── Nature of Appointment (CSC / DepEd standard) ─────────────────
        $natureOptions = [
            ['value' => 'Original',       'label' => 'Original',                    'sort' => 1],
            ['value' => 'Reemployment',  'label' => 'Reemployment',               'sort' => 2],
            ['value' => 'Reappointment',  'label' => 'Reappointment',               'sort' => 3],
            ['value' => 'Promotion',      'label' => 'Promotion',                   'sort' => 4],
            ['value' => 'Transfer',       'label' => 'Transfer',                    'sort' => 5],
            ['value' => 'Demotion',       'label' => 'Demotion',                    'sort' => 6],
            ['value' => 'Reclassification','label' => 'Reclassification',           'sort' => 7],

        ];

        foreach ($natureOptions as $opt) {
            AppointmentOption::updateOrCreate(
                ['option_type' => 'nature_appoint', 'option_value' => $opt['value']],
                ['option_label' => $opt['label'], 'sort_order' => $opt['sort'], 'is_active' => true]
            );
        }
    }
}
