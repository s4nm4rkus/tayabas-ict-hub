<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IctDtsRequest extends Model
{
    protected $table = 'ict_dts_requests';

    protected $fillable = [
        'ticket_no',
        'user_id',
        'date_reported',
        'dts_number',
        'requester_name',
        'mobile_number',
        'school',
        'request_type',
        // conditional
        'unit_name',
        'reason',
        'new_title',
        'edit_reason',
        'cancel_reason',
        'email_address',
        'document_type',
        'process_days',
        'new_email',
        // status
        'status',
        'admin_notes',
        'resolved_at',
    ];

    protected $casts = [
        'date_reported' => 'date',
        'resolved_at'   => 'datetime',
        'process_days'  => 'integer',
    ];

    // ── Ticket number generator ─────────────────────────────────────────────
    // Format: ICT-DTS-YYYY-NNNNN
    public static function generateTicketNo(): string
    {
        $year   = now()->year;
        $prefix = "ICT-DTS-{$year}-";
        $last   = static::where('ticket_no', 'like', $prefix . '%')
            ->orderByDesc('id')
            ->value('ticket_no');

        $seq = $last ? ((int) substr($last, -5)) + 1 : 1;
        return $prefix . str_pad($seq, 5, '0', STR_PAD_LEFT);
    }

    // ── Conditional field summary for display ───────────────────────────────
    public function conditionalSummary(): array
    {
        return match ($this->request_type) {
            'Retrieve'               => array_filter(['Unit' => $this->unit_name, 'Reason' => $this->reason]),
            'Edit Document Title'    => array_filter(['New Title' => $this->new_title, 'Reason' => $this->edit_reason]),
            'Cancel Transaction'     => array_filter(['Reason' => $this->cancel_reason]),
            'Reset Password'         => array_filter(['Email Address' => $this->email_address]),
            'Add Document'           => array_filter(['Document Type' => $this->document_type, 'Process Days' => $this->process_days]),
            'New User Email Address' => array_filter(['New Email' => $this->new_email]),
            default                  => [],
        };
    }

    // ── Status helpers ──────────────────────────────────────────────────────
    public function isPending(): bool
    {
        return $this->status === 'Pending';
    }
    public function isResolved(): bool
    {
        return $this->status === 'Resolved';
    }

    public function statusColor(): string
    {
        return match ($this->status) {
            'Pending'     => 'warning',
            'In Progress' => 'blue',
            'Resolved'    => 'success',
            'Closed'      => 'muted',
            'Cancelled'   => 'danger',
            default       => 'muted',
        };
    }

    // ── Relationships ───────────────────────────────────────────────────────
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}