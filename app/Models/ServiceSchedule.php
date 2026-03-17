<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceSchedule extends Model
{
    protected $fillable = [
        'vehicle_id',
        'service_type',
        'scheduled_date',
        'scheduled_odometer',
        'description',
        'estimated_cost',
        'actual_cost',
        'status',
        'completed_date',
        'notes',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'completed_date' => 'date',
        'estimated_cost' => 'decimal:2',
        'actual_cost'    => 'decimal:2',
    ];

    // ── Relasi ─────────────────────────────────────────────

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    // ── Scope ──────────────────────────────────────────────

    public function scopeUpcoming($query)
    {
        return $query->where('status', 'scheduled')
            ->where('scheduled_date', '>=', now());
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'scheduled')
            ->where('scheduled_date', '<', now());
    }

    // ── Accessor ───────────────────────────────────────────

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'scheduled'   => '<span class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-semibold text-blue-700">Terjadwal</span>',
            'in_progress' => '<span class="inline-flex items-center rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-semibold text-amber-700">Dalam Proses</span>',
            'completed'   => '<span class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-semibold text-emerald-700">Selesai</span>',
            'overdue'     => '<span class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-semibold text-red-700">Terlambat</span>',
            default       => '<span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-semibold text-slate-500">' . $this->status . '</span>',
        };
    }

    public function isOverdue(): bool
    {
        return $this->status === 'scheduled' && $this->scheduled_date->isPast();
    }

    public function getDaysUntilServiceAttribute(): int
    {
        return (int) now()->diffInDays($this->scheduled_date, false);
    }
}
