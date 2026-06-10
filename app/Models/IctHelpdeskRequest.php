<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IctHelpdeskRequest extends Model
{
    protected $table = 'ict_helpdesk_requests';

    protected $fillable = [
        'ticket_no',
        'user_id',
        'date_filed',
        'requesting_office',
        'requesting_name',
        'details_request',
        'date_requested',
        'time_requested',
        'specific_instructions',
        'status',
        'admin_notes',
        'resolved_at',
    ];

    protected $casts = [
        'date_filed'      => 'date',
        'date_requested'  => 'date',
        'resolved_at'     => 'datetime',
    ];

    // ── Ticket number generator ─────────────────────────────────────────────
    // Format: ICT-HD-YYYY-NNNNN
    public static function generateTicketNo(): string
    {
        $year   = now()->year;
        $prefix = "ICT-HD-{$year}-";
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

    // ── Relationships ───────────────────────────────────────────────────────
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}