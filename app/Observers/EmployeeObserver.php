<?php

namespace App\Observers;

use App\Models\AuditTrail;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;

class EmployeeObserver
{
    public function created(Employee $employee): void
    {
        $this->log("Added new employee: {$employee->full_name}");
    }

    public function updated(Employee $employee): void
    {
        $this->log("Updated employee: {$employee->full_name}");
    }

    public function deleted(Employee $employee): void
    {
        $this->log("Deleted employee: {$employee->full_name}");
    }

    private function log(string $action): void
    {
        if (!Auth::check()) return;

        AuditTrail::create([
            'user_id'     => Auth::id(),
            'action_done' => $action,
            'action_at'   => now(),
        ]);
    }
}