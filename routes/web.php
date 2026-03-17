<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\VehicleController;
use App\Http\Controllers\Admin\DriverController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Approver\ApprovalController;

// Auth routes (dari Breeze)
require __DIR__ . '/auth.php';

// Redirect root ke dashboard
Route::get('/', fn() => redirect()->route('dashboard'));

Route::middleware(['auth', 'verified'])->group(function () {

    // ── Notifikasi ────────────────────────────────────────
    Route::post('/notifications/read-all', function () {
        Auth::user()->unreadNotifications->markAsRead();
        return back();
    })->name('notifications.read-all');

    // ── Dashboard — semua role ────────────────────────────
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ── Admin routes ──────────────────────────────────────
    Route::middleware(['role:admin'])
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {

            // Booking
            Route::resource('bookings', BookingController::class);
            Route::post('bookings/{booking}/complete', [BookingController::class, 'complete'])
                ->name('bookings.complete');
            Route::post('bookings/{booking}/cancel', [BookingController::class, 'cancel'])
                ->name('bookings.cancel');

            // Vehicle
            Route::resource('vehicles', VehicleController::class);
            Route::post('vehicles/{vehicle}/fuel-log', [VehicleController::class, 'storeFuelLog'])
                ->name('vehicles.fuel-log');

            // Driver
            Route::resource('drivers', DriverController::class);

            // User — PENTING: route statis harus SEBELUM resource
            Route::get('users/departments', [UserController::class, 'getDepartments'])
                ->name('users.departments');             // ✅ nama jadi admin.users.departments
            Route::resource('users', UserController::class);

            // Reports
            Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
            Route::get('reports/export', [ReportController::class, 'export'])->name('reports.export');
        });

    // ── Approver routes ───────────────────────────────────
    Route::middleware(['role:approver|admin'])
        ->prefix('approvals')
        ->name('approvals.')
        ->group(function () {
            Route::get('/',                   [ApprovalController::class, 'index'])->name('index');
            Route::get('/{booking}',          [ApprovalController::class, 'show'])->name('show');
            Route::post('/{booking}/process', [ApprovalController::class, 'process'])->name('process');
        });
});
