<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Booking extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'booking_code',
        'requester_id',
        'vehicle_id',
        'driver_id',
        'purpose',
        'description',
        'destination',
        'passenger_count',
        'departure_at',
        'return_at',
        'odometer_start',
        'odometer_end',
        'status',
        'total_approver_levels',
        'current_approval_level',
        'cancellation_reason',
    ];

    protected $casts = [
        'departure_at' => 'datetime',
        'return_at'    => 'datetime',
    ];

    // ── Boot: auto-generate booking code ──────────────────
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($booking) {
            $booking->booking_code = 'VBS-' . now()->format('Ymd') . '-' . strtoupper(Str::random(4));
        });
    }

    // ── Relasi ─────────────────────────────────────────────
    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    public function approvals(): HasMany
    {
        return $this->hasMany(BookingApproval::class)->orderBy('level');
    }

    public function fuelLog(): HasMany
    {
        return $this->hasMany(FuelLog::class);
    }

    // ── Scope ──────────────────────────────────────────────
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeByRequester($query, $userId)
    {
        return $query->where('requester_id', $userId);
    }

    // ── Helper ─────────────────────────────────────────────
    public function isFullyApproved(): bool
    {
        return $this->current_approval_level >= $this->total_approver_levels;
    }

    public function getCurrentApproval(): ?BookingApproval
    {
        return $this->approvals()
            ->where('level', $this->current_approval_level + 1)
            ->first();
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'pending'    => '<span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-semibold text-slate-600">Menunggu</span>',
            'in_review'  => '<span class="inline-flex items-center rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-semibold text-amber-700">Direview</span>',
            'approved'   => '<span class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-semibold text-blue-700">Disetujui</span>',
            'rejected'   => '<span class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-semibold text-red-700">Ditolak</span>',
            'in_use'     => '<span class="inline-flex items-center rounded-full bg-purple-100 px-2.5 py-0.5 text-xs font-semibold text-purple-700">Sedang Jalan</span>',
            'completed'  => '<span class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-semibold text-emerald-700">Selesai</span>',
            'cancelled'  => '<span class="inline-flex items-center rounded-full bg-slate-200 px-2.5 py-0.5 text-xs font-semibold text-slate-500">Dibatalkan</span>',
            default      => '<span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-semibold text-slate-600">' . $this->status . '</span>',
        };
    }
}
