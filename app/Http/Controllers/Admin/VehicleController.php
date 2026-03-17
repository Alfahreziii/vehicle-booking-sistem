<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\Region;
use App\Models\FuelLog;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VehicleController extends Controller
{
    public function __construct(protected ActivityLogService $activityLogService) {}

    public function index(Request $request)
    {
        $vehicles = Vehicle::with('region')
            ->when(
                $request->search,
                fn($q) =>
                $q->where('plate_number', 'like', "%{$request->search}%")
                    ->orWhere('brand', 'like', "%{$request->search}%")
                    ->orWhere('model', 'like', "%{$request->search}%")
            )
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->type,   fn($q) => $q->where('type', $request->type))
            ->when($request->region_id, fn($q) => $q->where('region_id', $request->region_id))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $regions     = Region::orderBy('name')->get();
        $statusCounts = Vehicle::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('admin.vehicles.index', compact('vehicles', 'regions', 'statusCounts'));
    }

    public function create()
    {
        $regions = Region::orderBy('name')->get();
        return view('admin.vehicles.create', compact('regions'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'plate_number'        => ['required', 'string', 'unique:vehicles,plate_number'],
            'brand'               => ['required', 'string', 'max:100'],
            'model'               => ['required', 'string', 'max:100'],
            'year'                => ['required', 'integer', 'min:2000', 'max:' . now()->year],
            'type'                => ['required', 'in:passenger,cargo'],
            'ownership'           => ['required', 'in:owned,rented'],
            'rental_company'      => ['nullable', 'string', 'required_if:ownership,rented'],
            'region_id'           => ['required', 'exists:regions,id'],
            'fuel_consumption'    => ['nullable', 'numeric', 'min:1'],
            'current_odometer'    => ['nullable', 'integer', 'min:0'],
            'service_interval_km' => ['nullable', 'integer', 'min:1000'],
            'color'               => ['nullable', 'string'],
            'chassis_number'      => ['nullable', 'string', 'unique:vehicles,chassis_number'],
            'engine_number'       => ['nullable', 'string', 'unique:vehicles,engine_number'],
        ]);

        $vehicle = Vehicle::create($data);

        $this->activityLogService->log(
            action: 'created',
            subject: $vehicle,
            description: "Kendaraan {$vehicle->plate_number} ditambahkan oleh " . Auth::user()->name,
        );

        return redirect()
            ->route('admin.vehicles.show', $vehicle)
            ->with('success', "Kendaraan {$vehicle->plate_number} berhasil ditambahkan.");
    }

    public function show(Vehicle $vehicle)
    {
        $vehicle->load('region', 'fuelLogs.filledBy', 'serviceSchedules', 'bookings.requester');

        $totalFuelCost = $vehicle->fuelLogs->sum('total_cost');
        $totalFuelLiter = $vehicle->fuelLogs->sum('liters');

        return view('admin.vehicles.show', compact('vehicle', 'totalFuelCost', 'totalFuelLiter'));
    }

    public function edit(Vehicle $vehicle)
    {
        $regions = Region::orderBy('name')->get();
        return view('admin.vehicles.edit', compact('vehicle', 'regions'));
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        $data = $request->validate([
            'plate_number'        => ['required', 'string', 'unique:vehicles,plate_number,' . $vehicle->id],
            'brand'               => ['required', 'string', 'max:100'],
            'model'               => ['required', 'string', 'max:100'],
            'year'                => ['required', 'integer', 'min:2000', 'max:' . now()->year],
            'type'                => ['required', 'in:passenger,cargo'],
            'ownership'           => ['required', 'in:owned,rented'],
            'rental_company'      => ['nullable', 'string'],
            'region_id'           => ['required', 'exists:regions,id'],
            'status'              => ['required', 'in:available,in_use,maintenance,inactive'],
            'fuel_consumption'    => ['nullable', 'numeric', 'min:1'],
            'current_odometer'    => ['nullable', 'integer', 'min:0'],
            'service_interval_km' => ['nullable', 'integer', 'min:1000'],
            'color'               => ['nullable', 'string'],
        ]);

        $old = $vehicle->only(array_keys($data));
        $vehicle->update($data);

        $this->activityLogService->log(
            action: 'updated',
            subject: $vehicle,
            old: $old,
            new: $data,
            description: "Kendaraan {$vehicle->plate_number} diupdate oleh " . Auth::user()->name,
        );

        return redirect()
            ->route('admin.vehicles.show', $vehicle)
            ->with('success', "Kendaraan {$vehicle->plate_number} berhasil diupdate.");
    }

    public function destroy(Vehicle $vehicle)
    {
        $plate = $vehicle->plate_number;
        $vehicle->delete();

        $this->activityLogService->log(
            action: 'deleted',
            subject: $vehicle,
            description: "Kendaraan {$plate} dihapus oleh " . Auth::user()->name,
        );

        return redirect()
            ->route('admin.vehicles.index')
            ->with('success', "Kendaraan {$plate} berhasil dihapus.");
    }

    public function storeFuelLog(Request $request, Vehicle $vehicle)
    {
        $data = $request->validate([
            'liters'          => ['required', 'numeric', 'min:0.1'],
            'cost_per_liter'  => ['required', 'numeric', 'min:0'],
            'odometer_before' => ['required', 'integer', 'min:0'],
            'odometer_after'  => ['required', 'integer', 'gt:odometer_before'],
            'log_date'        => ['required', 'date', 'before_or_equal:today'],
            'fuel_station'    => ['nullable', 'string'],
            'booking_id'      => ['nullable', 'exists:bookings,id'],
            'notes'           => ['nullable', 'string'],
        ]);

        $data['total_cost'] = $data['liters'] * $data['cost_per_liter'];
        $data['vehicle_id'] = $vehicle->id;
        $data['filled_by']  = Auth::id();

        $fuelLog = FuelLog::create($data);

        // Update odometer kendaraan
        $vehicle->update(['current_odometer' => $data['odometer_after']]);

        $this->activityLogService->log(
            action: 'fuel_log_added',
            subject: $vehicle,
            description: "Log BBM {$data['liters']}L ditambahkan untuk {$vehicle->plate_number}",
        );

        return back()->with('success', 'Log BBM berhasil disimpan.');
    }
}
