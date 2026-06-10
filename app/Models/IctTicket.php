<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IctTicket extends Model
{
    protected $table = 'ict_tickets';

    protected $fillable = [
        'ticket_no',
        'user_id',
        'full_name',
        'position',
        'department',
        'date_reported',
        'assistance_types',
        'others_text',
        'description',
        'status',
        'admin_notes',
        'assigned_to',
        'resolved_at',
    ];

    protected $casts = [
        'assistance_types' => 'array',
        'date_reported'    => 'date',
        'resolved_at'      => 'datetime',
    ];

    // ── Status helpers ──────────────────────────────────────────────────────

    public function isPending(): bool
    {
        return $this->status === 'Pending';
    }
    public function isResolved(): bool
    {
        return $this->status === 'Resolved';
    }
    public function isClosed(): bool
    {
        return $this->status === 'Closed';
    }
    public function isCancelled(): bool
    {
        return $this->status === 'Cancelled';
    }

    /** Human-readable assistance list */
    public function assistanceSummary(): string
    {
        $types = $this->assistance_types ?? [];
        if (in_array('Others', $types) && $this->others_text) {
            $types = array_map(
                fn ($t) => $t === 'Others' ? 'Others: ' . $this->others_text : $t,
                $types
            );
        }
        return implode(', ', $types);
    }

    /** Auto-generate ticket number: ICT-YYYY-NNNNN */
    public static function generateTicketNo(): string
    {
        $year   = now()->year;
        $prefix = "ICT-{$year}-";
        $last   = static::where('ticket_no', 'like', $prefix . '%')
            ->orderByDesc('id')
            ->value('ticket_no');

        $seq = $last ? ((int) substr($last, -5)) + 1 : 1;
        return $prefix . str_pad($seq, 5, '0', STR_PAD_LEFT);
    }

    // ── Relationships ───────────────────────────────────────────────────────

    /** The user who submitted the ticket */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /** Employee profile of the submitter */
    // public function employee(): BelongsTo
    // {
    //     return $this->belongsTo(User::class, 'user_id')
    //         ->with('employee');
    // }

    /** ICT technician assigned to this ticket */
    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    // ── Scopes ──────────────────────────────────────────────────────────────

    public function scopePending($q)
    {
        return $q->where('status', 'Pending');
    }
    public function scopeInProgress($q)
    {
        return $q->where('status', 'In Progress');
    }
    public function scopeResolved($q)
    {
        return $q->where('status', 'Resolved');
    }

    // ── Status badge color (for views) ──────────────────────────────────────

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
}
