<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FuelLog extends Model
{
    protected $fillable = [
        'booking_id',
        'vehicle_id',
        'filled_by',
        'liters',
        'cost_per_liter',
        'total_cost',
        'odometer_before',
        'odometer_after',
        'log_date',
        'fuel_station',
        'notes',
    ];

    protected $casts = [
        'log_date'       => 'date',
        'liters'         => 'decimal:2',
        'cost_per_liter' => 'decimal:2',
        'total_cost'     => 'decimal:2',
    ];

    // ── Relasi ─────────────────────────────────────────────

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function filledBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'filled_by');
    }

    // ── Accessor ───────────────────────────────────────────

    public function getDistanceTraveledAttribute(): int
    {
        return $this->odometer_after - $this->odometer_before;
    }

    public function getFuelEfficiencyAttribute(): ?float
    {
        if ($this->liters <= 0) return null;
        return round($this->distance_traveled / $this->liters, 2);
    }
}
