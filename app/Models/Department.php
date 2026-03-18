<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    protected $fillable = ['name', 'region_id', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    // ── Relasi ─────────────────────────────────────────────

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    // ── Scope ──────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
