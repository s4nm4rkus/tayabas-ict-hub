<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('service-records:regenerate')
    ->yearlyOn(1, 1, '00:00')
    ->withoutOverlapping();

Schedule::command('leave:compute-monthly')
    ->monthlyOn(1, '00:30')
    ->withoutOverlapping()
    ->onFailure(function () {
        \Illuminate\Support\Facades\Log::error('Scheduled monthly leave computation FAILED to run.');
    });
