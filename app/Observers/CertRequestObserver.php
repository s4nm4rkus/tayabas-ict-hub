<?php

namespace App\Observers;

use App\Models\AuditTrail;
use App\Models\CertRequest;
use Illuminate\Support\Facades\Auth;

class CertRequestObserver
{
    public function created(CertRequest $certRequest): void
    {
        $this->log("Requested certificate: {$certRequest->req_type}");
    }

    public function updated(CertRequest $certRequest): void
    {
        $this->log("Certificate request {$certRequest->req_type} updated to: {$certRequest->req_status}");
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
