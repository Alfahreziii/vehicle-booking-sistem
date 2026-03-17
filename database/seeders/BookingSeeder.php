<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Booking;
use App\Models\BookingApproval;
use App\Models\Vehicle;
use App\Models\Driver;
use App\Models\User;
use App\Models\FuelLog;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        $admin    = User::where('email', 'admin.pool@nikelmining.co.id')->first();
        $approver1 = User::where('email', 'kabag.ops@nikelmining.co.id')->first();
        $approver2 = User::where('email', 'manager.ops@nikelmining.co.id')->first();
        $vehicles  = Vehicle::where('type', 'passenger')->take(4)->get();
        $drivers   = Driver::with('user')->take(4)->get();
        $requester = User::where('email', 'ahmad.fauzi@nikelmining.co.id')->first();

        $samples = [
            // Booking 1 — Selesai (completed)
            [
                'status'           => 'completed',
                'departure_at'     => now()->subDays(10),
                'return_at'        => now()->subDays(9),
                'odometer_start'   => 15000,
                'odometer_end'     => 15280,
                'purpose'          => 'Kunjungan klien ke kantor mitra',
                'destination'      => 'Gedung Midplaza, Jakarta Pusat',
                'passenger_count'  => 3,
                'approval_status'  => 'approved',
            ],
            // Booking 2 — Sedang digunakan (in_use)
            [
                'status'           => 'in_use',
                'departure_at'     => now()->subHours(3),
                'return_at'        => now()->addHours(5),
                'odometer_start'   => 32000,
                'odometer_end'     => null,
                'purpose'          => 'Pengiriman dokumen ke Kementerian ESDM',
                'destination'      => 'Kementerian ESDM, Jakarta Pusat',
                'passenger_count'  => 2,
                'approval_status'  => 'approved',
            ],
            // Booking 3 — Menunggu approval level 1
            [
                'status'           => 'in_review',
                'departure_at'     => now()->addDays(2),
                'return_at'        => now()->addDays(3),
                'odometer_start'   => null,
                'odometer_end'     => null,
                'purpose'          => 'Survey lokasi tambang baru',
                'destination'      => 'Morowali Utara, Sulawesi Tengah',
                'passenger_count'  => 4,
                'approval_status'  => 'waiting_l1',
            ],
            // Booking 4 — Ditolak
            [
                'status'           => 'rejected',
                'departure_at'     => now()->subDays(3),
                'return_at'        => now()->subDays(2),
                'odometer_start'   => null,
                'odometer_end'     => null,
                'purpose'          => 'Keperluan pribadi',
                'destination'      => 'Bogor, Jawa Barat',
                'passenger_count'  => 1,
                'approval_status'  => 'rejected',
            ],
            // Booking 5 — Menunggu approval level 2
            [
                'status'           => 'in_review',
                'departure_at'     => now()->addDays(1),
                'return_at'        => now()->addDays(2),
                'odometer_start'   => null,
                'odometer_end'     => null,
                'purpose'          => 'Rapat koordinasi dengan kontraktor tambang',
                'destination'      => 'Hotel Aryaduta, Jakarta',
                'passenger_count'  => 5,
                'approval_status'  => 'waiting_l2',
            ],
        ];

        foreach ($samples as $i => $sample) {
            $vehicle = $vehicles[$i % $vehicles->count()];
            $driver  = $drivers[$i % $drivers->count()];

            $booking = Booking::create([
                'booking_code'          => 'VBS-' . now()->format('Ymd') . '-' . strtoupper(Str::random(4)),
                'requester_id'          => $requester->id,
                'vehicle_id'            => $vehicle->id,
                'driver_id'             => $driver->id,
                'purpose'               => $sample['purpose'],
                'description'           => 'Data dummy untuk keperluan testing sistem.',
                'destination'           => $sample['destination'],
                'passenger_count'       => $sample['passenger_count'],
                'departure_at'          => $sample['departure_at'],
                'return_at'             => $sample['return_at'],
                'odometer_start'        => $sample['odometer_start'],
                'odometer_end'          => $sample['odometer_end'],
                'status'                => $sample['status'],
                'total_approver_levels' => 2,
                'current_approval_level' => match ($sample['approval_status']) {
                    'approved'   => 2,
                    'waiting_l1' => 0,
                    'waiting_l2' => 1,
                    'rejected'   => 1,
                    default      => 0,
                },
            ]);

            // Buat record BookingApproval sesuai status
            $this->createApprovals($booking, $sample['approval_status'], $approver1, $approver2);

            // Buat fuel log untuk yang sudah selesai
            if ($sample['status'] === 'completed') {
                FuelLog::create([
                    'booking_id'      => $booking->id,
                    'vehicle_id'      => $vehicle->id,
                    'filled_by'       => $admin->id,
                    'liters'          => 25.5,
                    'cost_per_liter'  => 10000,
                    'total_cost'      => 255000,
                    'odometer_before' => $sample['odometer_start'],
                    'odometer_after'  => $sample['odometer_end'],
                    'log_date'        => $sample['departure_at'],
                    'fuel_station'    => 'SPBU Pertamina Jl. Gatot Subroto',
                    'notes'           => 'Pengisian BBM sebelum keberangkatan',
                ]);
            }
        }
    }

    private function createApprovals(Booking $booking, string $approvalStatus, User $approver1, User $approver2): void
    {
        match ($approvalStatus) {
            'approved' => $this->setApproved($booking, $approver1, $approver2),
            'rejected' => $this->setRejected($booking, $approver1),
            'waiting_l1' => $this->setWaitingL1($booking, $approver1, $approver2),
            'waiting_l2' => $this->setWaitingL2($booking, $approver1, $approver2),
            default => null,
        };
    }

    private function setApproved(Booking $b, User $a1, User $a2): void
    {
        BookingApproval::create(['booking_id' => $b->id, 'approver_id' => $a1->id, 'level' => 1, 'status' => 'approved', 'acted_at' => now()->subDays(9), 'notes' => 'Disetujui.']);
        BookingApproval::create(['booking_id' => $b->id, 'approver_id' => $a2->id, 'level' => 2, 'status' => 'approved', 'acted_at' => now()->subDays(9), 'notes' => 'Setuju, silakan berangkat.']);
    }

    private function setRejected(Booking $b, User $a1): void
    {
        BookingApproval::create(['booking_id' => $b->id, 'approver_id' => $a1->id, 'level' => 1, 'status' => 'rejected', 'acted_at' => now()->subDays(2), 'notes' => 'Ditolak, tidak sesuai keperluan dinas.']);
        BookingApproval::create(['booking_id' => $b->id, 'approver_id' => $a1->id, 'level' => 2, 'status' => 'waiting', 'acted_at' => null, 'notes' => null]);
    }

    private function setWaitingL1(Booking $b, User $a1, User $a2): void
    {
        BookingApproval::create(['booking_id' => $b->id, 'approver_id' => $a1->id, 'level' => 1, 'status' => 'waiting', 'acted_at' => null, 'notes' => null]);
        BookingApproval::create(['booking_id' => $b->id, 'approver_id' => $a2->id, 'level' => 2, 'status' => 'waiting', 'acted_at' => null, 'notes' => null]);
    }

    private function setWaitingL2(Booking $b, User $a1, User $a2): void
    {
        BookingApproval::create(['booking_id' => $b->id, 'approver_id' => $a1->id, 'level' => 1, 'status' => 'approved', 'acted_at' => now()->subHours(5), 'notes' => 'Disetujui level 1.']);
        BookingApproval::create(['booking_id' => $b->id, 'approver_id' => $a2->id, 'level' => 2, 'status' => 'waiting', 'acted_at' => null, 'notes' => null]);
    }
}
