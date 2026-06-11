<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'user_id'     => 'SDOHUB-2026-0001',
            'username'    => 'tayabas.icthosting@gmail.com',
            'password'    => bcrypt('123456789'),
            'user_pos'    => 'Super Administrator',
            'user_stat'   => 'Enabled',
            'pass_change' => 1,
        ]);
    }
}
