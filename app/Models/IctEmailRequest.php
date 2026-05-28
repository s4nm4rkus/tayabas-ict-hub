<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IctEmailRequest extends Model
{
    protected $table = 'ict_email_requests';

    protected $fillable = [
        'ticket_no',
        'user_id',
        'date_reported',
        'request_type',
        'full_name',
        'personal_email',
        'cellphone',
        'school_name',
        'status',
        'admin_notes',
        'resolved_at',
    ];

    protected $casts = [
        'date_reported' => 'date',
        'resolved_at'   => 'datetime',
    ];

    // ── Ticket number generator ─────────────────────────────────────────────
    // Format: ICT-EMAIL-YYYY-NNNNN
    public static function generateTicketNo(): string
    {
        $year   = now()->year;
        $prefix = "ICT-EMAIL-{$year}-";
        $last   = static::where('ticket_no', 'like', $prefix . '%')
            ->orderByDesc('id')
            ->value('ticket_no');

        $seq = $last ? ((int) substr($last, -5)) + 1 : 1;
        return $prefix . str_pad($seq, 5, '0', STR_PAD_LEFT);
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
