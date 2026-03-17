<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehicle extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'plate_number',
        'brand',
        'model',
        'year',
        'type',
        'ownership',
        'rental_company',
        'region_id',
        'status',
        'fuel_consumption',
        'current_odometer',
        'last_service_date',
        'service_interval_km',
        'color',
        'chassis_number',
        'engine_number',
        'is_active',
    ];

    protected $casts = [
        'last_service_date' => 'date',
        'is_active'         => 'boolean',
        'year'              => 'integer',
    ];

    // ── Relasi ─────────────────────────────────────────────
    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function fuelLogs(): HasMany
    {
        return $this->hasMany(FuelLog::class);
    }

    public function serviceSchedules(): HasMany
    {
        return $this->hasMany(ServiceSchedule::class);
    }

    // ── Scope ──────────────────────────────────────────────
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available')->where('is_active', true);
    }

    public function scopeByRegion($query, $regionId)
    {
        return $query->where('region_id', $regionId);
    }

    // ── Accessor ───────────────────────────────────────────
    public function getTypeLabelAttribute(): string
    {
        return $this->type === 'passenger' ? 'Angkutan Orang' : 'Angkutan Barang';
    }

    public function getOwnershipLabelAttribute(): string
    {
        return $this->ownership === 'owned' ? 'Milik Perusahaan' : 'Kendaraan Sewa';
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'available'   => '<span class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-semibold text-emerald-700">Tersedia</span>',
            'in_use'      => '<span class="inline-flex items-center rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-semibold text-amber-700">Digunakan</span>',
            'maintenance' => '<span class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-semibold text-red-700">Perawatan</span>',
            'inactive'    => '<span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-semibold text-slate-500">Tidak Aktif</span>',
            default       => '<span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-semibold text-slate-500">' . $this->status . '</span>',
        };
    }

    public function isServiceDue(): bool
    {
        if (!$this->last_service_date) return true;
        $kmSinceService = $this->current_odometer - ($this->fuelLogs()->where('log_date', '>=', $this->last_service_date)->sum('odometer_after') ?: 0);
        return $kmSinceService >= $this->service_interval_km;
    }
}
