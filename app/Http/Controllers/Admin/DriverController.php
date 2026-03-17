<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DriverController extends Controller
{
    public function __construct(protected ActivityLogService $activityLogService) {}

    public function index(Request $request)
    {
        $drivers = Driver::with(['user.region', 'user.department'])
            ->when(
                $request->search,
                fn($q) =>
                $q->whereHas(
                    'user',
                    fn($q2) =>
                    $q2->where('name', 'like', "%{$request->search}%")
                        ->orWhere('employee_id', 'like', "%{$request->search}%")
                )
                    ->orWhere('license_number', 'like', "%{$request->search}%")
            )
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->license_type, fn($q) => $q->where('license_type', $request->license_type))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $statusCounts = Driver::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('admin.drivers.index', compact('drivers', 'statusCounts'));
    }

    public function create()
    {
        // Hanya user yang belum jadi driver dan punya role driver
        $users = User::role('driver')
            ->whereDoesntHave('driver')
            ->orderBy('name')
            ->get();

        return view('admin.drivers.create', compact('users'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id'        => ['required', 'exists:users,id', 'unique:drivers,user_id'],
            'license_number' => ['required', 'string', 'unique:drivers,license_number'],
            'license_type'   => ['required', 'in:A,B1,B2,C'],
            'license_expiry' => ['required', 'date', 'after:today'],
            'status'         => ['required', 'in:available,on_duty,off,inactive'],
        ]);

        $driver = Driver::create($data);
        $driver->load('user');

        $this->activityLogService->log(
            action: 'created',
            subject: $driver,
            description: "Driver {$driver->user->name} ditambahkan oleh " . Auth::user()->name,
        );

        return redirect()
            ->route('admin.drivers.show', $driver)
            ->with('success', "Driver {$driver->user->name} berhasil ditambahkan.");
    }

    public function show(Driver $driver)
    {
        $driver->load([
            'user.region',
            'user.department',
            'bookings.vehicle',
            'bookings.requester',
        ]);

        $totalBookings    = $driver->bookings->count();
        $completedBookings = $driver->bookings->where('status', 'completed')->count();

        return view('admin.drivers.show', compact('driver', 'totalBookings', 'completedBookings'));
    }

    public function edit(Driver $driver)
    {
        $driver->load('user');
        return view('admin.drivers.edit', compact('driver'));
    }

    public function update(Request $request, Driver $driver)
    {
        $data = $request->validate([
            'license_number' => ['required', 'string', 'unique:drivers,license_number,' . $driver->id],
            'license_type'   => ['required', 'in:A,B1,B2,C'],
            'license_expiry' => ['required', 'date'],
            'status'         => ['required', 'in:available,on_duty,off,inactive'],
        ]);

        $old = $driver->only(array_keys($data));
        $driver->update($data);

        $this->activityLogService->log(
            action: 'updated',
            subject: $driver,
            old: $old,
            new: $data,
            description: "Data driver {$driver->user->name} diupdate oleh " . Auth::user()->name,
        );

        return redirect()
            ->route('admin.drivers.show', $driver)
            ->with('success', "Data driver berhasil diupdate.");
    }

    public function destroy(Driver $driver)
    {
        $name = $driver->user->name;
        $driver->delete();

        $this->activityLogService->log(
            action: 'deleted',
            subject: $driver,
            description: "Driver {$name} dihapus oleh " . Auth::user()->name,
        );

        return redirect()
            ->route('admin.drivers.index')
            ->with('success', "Driver {$name} berhasil dihapus.");
    }
}
