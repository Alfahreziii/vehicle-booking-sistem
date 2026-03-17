<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Auth;

class ActivityLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'action',
        'model_type',
        'model_id',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
        'description',
    ];

    protected $casts = [
        'old_values'  => 'array',
        'new_values'  => 'array',
        'created_at'  => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subject(): MorphTo
    {
        return $this->morphTo('model');
    }

    // Static helper untuk mudah mencatat log dari mana saja
    public static function record(string $action, Model $subject, array $old = [], array $new = [], string $description = ''): self
    {
        return self::create([
            'user_id'     => Auth::id(),
            'action'      => $action,
            'model_type'  => get_class($subject),
            'model_id'    => $subject->getKey(),
            'old_values'  => $old,
            'new_values'  => $new,
            'ip_address'  => request()->ip(),
            'user_agent'  => request()->userAgent(),
            'description' => $description,
        ]);
    }
}
