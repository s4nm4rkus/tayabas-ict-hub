<?php

namespace App\Mail;

use App\Models\Leave;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LeaveApprovedMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public Leave $leave;

    public function __construct(Leave $leave)
    {
        $this->leave = $leave;
    }

    public function build()
    {
        $subject = $this->leave->leave_status === 'Approved'
            ? 'Your Leave Application Has Been Approved — Tayabas ICT Hub'
            : 'Your Leave Application Has Been Declined — Tayabas ICT Hub';


        return $this->subject($subject)
            ->view('emails.leave_approved')
            ->with(['leave' => $this->leave]);

    }
}
