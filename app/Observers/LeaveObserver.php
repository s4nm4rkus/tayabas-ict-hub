<?php

namespace App\Observers;

use App\Models\AuditTrail;
use App\Models\Leave;
use Illuminate\Support\Facades\Auth;

class LeaveObserver
{
    public function created(Leave $leave): void
    {
        $this->log("Submitted leave application: {$leave->leavetype}");
    }

    public function updated(Leave $leave): void
    {
        $this->log("Leave status updated to: {$leave->leave_status} for {$leave->fullname}");
    }

    private function log(string $action): void
    {
        if (! Auth::check()) {
            return;
        }

        AuditTrail::create([
            'user_id' => Auth::id(),
            'action_done' => $action,
            'action_at' => now(),
        ]);
    }
}
