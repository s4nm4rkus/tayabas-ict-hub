<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppointmentOption extends Model
{
    protected $table = 'appointment_options';

    protected $fillable = [
        'option_type',
        'option_value',
        'option_label',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    // ── Scopes ────────────────────────────────────────────────────────────
    public function scopeNature($query)
    {
        return $query->where('option_type', 'nature_appoint')->where('is_active', true)->orderBy('sort_order');
    }

    public function scopeStatus($query)
    {
        return $query->where('option_type', 'status_appoint')->where('is_active', true)->orderBy('sort_order');
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('option_type', $type)->where('is_active', true)->orderBy('sort_order');
    }
}
