<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ActivityLogService
{
    /**
     * Log dengan subject model (untuk booking, vehicle, dll)
     */
    public function log(
        string $action,
        Model $subject,
        array $old = [],
        array $new = [],
        string $description = ''
    ): ActivityLog {
        return ActivityLog::create([
            'user_id'     => Auth::id() ?? null,
            'action'      => $action,
            'model_type'  => get_class($subject),
            'model_id'    => $subject->getKey(),
            'old_values'  => $old ?: null,
            'new_values'  => $new ?: null,
            'ip_address'  => request()->ip(),
            'user_agent'  => request()->userAgent(),
            'description' => $description,
        ]);
    }

    /**
     * Log tanpa subject model — untuk aksi general
     * seperti export, login, logout, dll
     */
    public function logAction(
        string $action,
        string $description = '',
        array $new = [],
    ): ActivityLog {
        return ActivityLog::create([
            'user_id'     => Auth::id() ?? null,
            'action'      => $action,
            'model_type'  => null,
            'model_id'    => null,
            'old_values'  => null,
            'new_values'  => $new ?: null,
            'ip_address'  => request()->ip(),
            'user_agent'  => request()->userAgent(),
            'description' => $description,
        ]);
    }
}
