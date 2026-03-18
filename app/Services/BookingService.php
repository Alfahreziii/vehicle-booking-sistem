<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\BookingApproval;
use App\Models\Vehicle;
use App\Models\Driver;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class BookingService
{
    public function __construct(
        protected NotificationService $notificationService,
        protected ActivityLogService $activityLogService,
    ) {}

    /**
     * Buat pemesanan baru beserta approval chain-nya
     */
    public function create(array $data): Booking
    {
        return DB::transaction(function () use ($data) {

            // 1. Cek ketersediaan kendaraan
            $this->ensureVehicleAvailable($data['vehicle_id'], $data['departure_at'], $data['return_at']);

            // 2. Cek ketersediaan driver
            $this->ensureDriverAvailable($data['driver_id'], $data['departure_at'], $data['return_at']);

            // 3. Buat booking
            $booking = Booking::create([
                'requester_id'           => Auth::id(),
                'vehicle_id'             => $data['vehicle_id'],
                'driver_id'              => $data['driver_id'],
                'purpose'                => $data['purpose'],
                'description'            => $data['description'] ?? null,
                'destination'            => $data['destination'],
                'passenger_count'        => $data['passenger_count'],
                'departure_at'           => $data['departure_at'],
                'return_at'              => $data['return_at'],
                'status'                 => 'in_review',
                'total_approver_levels'  => count($data['approvers']),
                'current_approval_level' => 0,
            ]);

            // 4. Buat approval chain sesuai urutan approver yang dipilih
            foreach ($data['approvers'] as $level => $approverId) {
                BookingApproval::create([
                    'booking_id'  => $booking->id,
                    'approver_id' => $approverId,
                    'level'       => $level + 1,
                    'status'      => 'waiting',
                ]);
            }

            // 5. Set kendaraan & driver jadi tidak tersedia
            Vehicle::where('id', $data['vehicle_id'])->update(['status' => 'in_use']);
            Driver::where('id', $data['driver_id'])->update(['status' => 'on_duty']);

            // 6. Kirim notifikasi ke approver level 1
            $firstApproval = $booking->approvals()->where('level', 1)->first();
            $this->notificationService->sendToApprover($booking, $firstApproval);

            // 7. Catat activity log
            $this->activityLogService->log(
                action: 'created',
                subject: $booking,
                description: "Booking {$booking->booking_code} dibuat oleh " . Auth::user()->name,
            );

            Log::info("Booking created: {$booking->booking_code}", ['user_id' => Auth::id()]);

            return $booking;
        });
    }

    /**
     * Admin batalkan booking
     */
    public function cancel(Booking $booking, string $reason): Booking
    {
        return DB::transaction(function () use ($booking, $reason) {

            $oldStatus = $booking->status;

            $booking->update([
                'status'               => 'cancelled',
                'cancellation_reason'  => $reason,
            ]);

            // Bebaskan kendaraan & driver
            $booking->vehicle->update(['status' => 'available']);
            $booking->driver->update(['status' => 'available']);

            $this->activityLogService->log(
                action: 'cancelled',
                subject: $booking,
                old: ['status' => $oldStatus],
                new: ['status' => 'cancelled', 'reason' => $reason],
                description: "Booking {$booking->booking_code} dibatalkan.",
            );

            return $booking->fresh();
        });
    }

    /**
     * Admin update odometer & selesaikan booking
     */
    public function complete(Booking $booking, int $odometerEnd, array $fuelData = []): Booking
    {
        return DB::transaction(function () use ($booking, $odometerEnd, $fuelData) {

            $booking->update([
                'status'       => 'completed',
                'odometer_end' => $odometerEnd,
            ]);

            $vehicle = $booking->vehicle()->first();
            $vehicle?->update([
                'status'           => 'available',
                'current_odometer' => $odometerEnd,
            ]);
            $booking->driver()->first()?->update(['status' => 'available']);

            // Simpan fuel log jika data BBM diisi
            if (!empty($fuelData['fuel_liters']) && !empty($fuelData['fuel_cost_per_liter'])) {
                \App\Models\FuelLog::create([
                    'booking_id'      => $booking->id,
                    'vehicle_id'      => $booking->vehicle_id,
                    'filled_by'       => Auth::id(),
                    'liters'          => $fuelData['fuel_liters'],
                    'cost_per_liter'  => $fuelData['fuel_cost_per_liter'],
                    'total_cost'      => $fuelData['fuel_liters'] * $fuelData['fuel_cost_per_liter'],
                    'odometer_before' => $booking->odometer_start ?? $vehicle?->current_odometer ?? 0,
                    'odometer_after'  => $odometerEnd,
                    'log_date'        => now()->toDateString(),
                ]);
            }

            $this->activityLogService->log(
                action: 'completed',
                subject: $booking,
                description: "Booking {$booking->booking_code} selesai. Odometer: {$odometerEnd} km.",
            );

            return $booking->fresh();
        });
    }

    // ── Private helpers ─────────────────────────────────────

    private function ensureVehicleAvailable(int $vehicleId, string $departureAt, string $returnAt): void
    {
        $conflict = Booking::where('vehicle_id', $vehicleId)
            ->whereNotIn('status', ['cancelled', 'rejected', 'completed'])
            ->where(function ($q) use ($departureAt, $returnAt) {
                $q->whereBetween('departure_at', [$departureAt, $returnAt])
                    ->orWhereBetween('return_at', [$departureAt, $returnAt])
                    ->orWhere(function ($q2) use ($departureAt, $returnAt) {
                        $q2->where('departure_at', '<=', $departureAt)
                            ->where('return_at', '>=', $returnAt);
                    });
            })->exists();

        if ($conflict) {
            throw new \Exception('Kendaraan tidak tersedia pada rentang waktu tersebut.');
        }
    }

    private function ensureDriverAvailable(int $driverId, string $departureAt, string $returnAt): void
    {
        $conflict = Booking::where('driver_id', $driverId)
            ->whereNotIn('status', ['cancelled', 'rejected', 'completed'])
            ->where(function ($q) use ($departureAt, $returnAt) {
                $q->whereBetween('departure_at', [$departureAt, $returnAt])
                    ->orWhereBetween('return_at', [$departureAt, $returnAt]);
            })->exists();

        if ($conflict) {
            throw new \Exception('Driver tidak tersedia pada rentang waktu tersebut.');
        }
    }
}
