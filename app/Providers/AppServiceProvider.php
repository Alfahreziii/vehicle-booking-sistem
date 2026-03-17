<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\BookingService;
use App\Services\NotificationService;
use App\Services\ActivityLogService;
use App\Services\ApprovalService;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ApprovalService::class, function ($app) {
            return new ApprovalService(
                $app->make(NotificationService::class),
                $app->make(ActivityLogService::class),
            );
        });
        $this->app->bind(ActivityLogService::class);
        $this->app->bind(NotificationService::class);
        $this->app->bind(BookingService::class, function ($app) {
            return new BookingService(
                $app->make(NotificationService::class),
                $app->make(ActivityLogService::class),
            );
        });
    }

    public function boot(): void
    {
        \Carbon\Carbon::setLocale('id');
        \Illuminate\Support\Facades\Date::setLocale('id');
    }
}
