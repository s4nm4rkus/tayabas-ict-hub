<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Employee;
use App\Models\Leave;
use App\Models\CertRequest;
use App\Observers\EmployeeObserver;
use App\Observers\LeaveObserver;
use App\Observers\CertRequestObserver;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Employee::observe(EmployeeObserver::class);
        Leave::observe(LeaveObserver::class);
        CertRequest::observe(CertRequestObserver::class);
    }
}