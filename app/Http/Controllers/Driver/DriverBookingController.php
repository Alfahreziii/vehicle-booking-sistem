<?php
// app/Http/Controllers/Driver/DriverBookingController.php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DriverBookingController extends Controller
{
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Ambil driver record milik user ini
        $driver = $user->driver;

        if (!$driver) {
            abort(404, 'Data driver tidak ditemukan.');
        }

        $bookings = Booking::with(['requester.region', 'vehicle'])
            ->where('driver_id', $driver->id)
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $statusCounts = Booking::where('driver_id', $driver->id)
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('driver.bookings.index', compact('bookings', 'statusCounts', 'driver'));
    }

    public function show(Booking $booking)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Pastikan booking ini memang milik driver ini
        if ($booking->driver_id !== $user->driver?->id) {
            abort(403, 'Anda tidak memiliki akses ke booking ini.');
        }

        $booking->load([
            'requester.region',
            'requester.department',
            'vehicle.region',
            'driver.user',
            'approvals.approver',
        ]);

        return view('driver.bookings.show', compact('booking'));
    }
}
