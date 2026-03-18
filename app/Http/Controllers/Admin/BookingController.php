<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookingRequest;
use App\Services\BookingService;
use App\Models\Booking;
use App\Models\Vehicle;
use App\Models\Driver;
use App\Models\User;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function __construct(protected BookingService $bookingService) {}

    public function index(Request $request)
    {
        $bookings = Booking::with(['requester', 'vehicle', 'driver.user', 'approvals'])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->search, fn($q) => $q->where(function ($q2) use ($request) {
                $q2->where('booking_code', 'like', "%{$request->search}%")
                    ->orWhere('purpose', 'like', "%{$request->search}%")
                    ->orWhere('destination', 'like', "%{$request->search}%");
            }))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $statusCounts = Booking::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('admin.bookings.index', compact('bookings', 'statusCounts'));
    }

    public function create()
    {
        $vehicles  = Vehicle::with('region')->available()->get();
        $drivers   = Driver::with('user')->available()->get();
        $approvers = User::role('approver')->orderBy('name')->get();

        return view('admin.bookings.create', compact('vehicles', 'drivers', 'approvers'));
    }

    public function store(StoreBookingRequest $request)
    {
        try {
            $booking = $this->bookingService->create($request->validated());

            return redirect()
                ->route('admin.bookings.show', $booking)
                ->with('success', "Booking {$booking->booking_code} berhasil dibuat dan dikirim untuk persetujuan.");
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function show(Booking $booking)
    {
        $booking->load([
            'requester',
            'vehicle.region',
            'driver.user',
            'approvals.approver',
            'fuelLog',
        ]);

        return view('admin.bookings.show', compact('booking'));
    }

    public function complete(Request $request, Booking $booking)
    {
        $request->validate([
            'odometer_end'        => ['required', 'integer', 'min:' . ($booking->odometer_start ?? 0)],
            'fuel_liters'         => ['nullable', 'numeric', 'min:0.1'],
            'fuel_cost_per_liter' => [
                'nullable',
                'numeric',
                'min:0',
                'required_with:fuel_liters'
            ],
        ]);

        try {
            $this->bookingService->complete(
                $booking,
                $request->odometer_end,
                // Data BBM opsional
                $request->only(['fuel_liters', 'fuel_cost_per_liter'])
            );

            return back()->with('success', 'Booking berhasil diselesaikan.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function cancel(Request $request, Booking $booking)
    {
        $request->validate([
            'cancellation_reason' => ['required', 'string', 'min:10'],
        ]);

        try {
            $this->bookingService->cancel($booking, $request->cancellation_reason);

            return back()->with('success', 'Booking berhasil dibatalkan.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
