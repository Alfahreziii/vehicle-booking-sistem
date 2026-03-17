<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Driver extends Model
{
    protected $fillable = [
        'user_id',
        'license_number',
        'license_type',
        'license_expiry',
        'status',
    ];

    protected $casts = [
        'license_expiry' => 'date',
    ];

    // ── Relasi ─────────────────────────────────────────────
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    // ── Scope ──────────────────────────────────────────────
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    // ── Accessor ───────────────────────────────────────────
    public function getNameAttribute(): string
    {
        return $this->user?->name ?? '-';
    }

    public function isLicenseExpired(): bool
    {
        return $this->license_expiry->isPast();
    }

    public function getLicenseStatusAttribute(): string
    {
        if ($this->isLicenseExpired()) {
            return '<span class="badge bg-danger">SIM Expired</span>';
        }

        if ($this->license_expiry->diffInDays(now()) <= 30) {
            return '<span class="badge bg-warning">SIM Segera Expired</span>';
        }

        return '<span class="badge bg-success">SIM Aktif</span>';
    }
}
