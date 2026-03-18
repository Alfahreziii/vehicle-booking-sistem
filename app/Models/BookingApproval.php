<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingApproval extends Model
{
    protected $fillable = [
        'booking_id',
        'approver_id',
        'level',
        'status',
        'notes',
        'acted_at',
    ];

    protected $casts = [
        'acted_at' => 'datetime',
    ];

    // ── Relasi ─────────────────────────────────────────────

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approver_id');
    }

    // ── Scope ──────────────────────────────────────────────

    public function scopeWaiting($query)
    {
        return $query->where('status', 'waiting');
    }

    public function scopeForApprover($query, $userId)
    {
        return $query->where('approver_id', $userId);
    }

    public function isWaiting(): bool
    {
        return $this->status === 'waiting';
    }
}
