<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Region extends Model
{
    protected $fillable = ['name', 'type', 'location', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];


    // ── Relasi ─────────────────────────────────────────────

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class);
    }

    public function departments(): HasMany
    {
        return $this->hasMany(Department::class);
    }

    // ── Scope ──────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'head_office'   => 'Kantor Pusat',
            'branch_office' => 'Kantor Cabang',
            'mine'          => 'Tambang',
            default         => $this->type,
        };
    }
}
