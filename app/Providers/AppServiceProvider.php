<?php

namespace App\Providers;

use App\Models\CertRequest;
use App\Models\Employee;
use App\Models\Leave;
use App\Observers\CertRequestObserver;
use App\Observers\EmployeeObserver;
use App\Observers\LeaveObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Employee::observe(EmployeeObserver::class);
        Leave::observe(LeaveObserver::class);
        CertRequest::observe(CertRequestObserver::class);
    }
}
