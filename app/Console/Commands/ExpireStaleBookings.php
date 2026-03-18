<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Services\ActivityLogService;
use Illuminate\Console\Command;

class ExpireStaleBookings extends Command
{
    protected $signature   = 'bookings:expire-stale';
    protected $description = 'Auto-expire booking yang sudah lewat tanggal berangkat tapi belum disetujui';

    public function __construct(protected ActivityLogService $activityLogService)
    {
        parent::__construct();
    }

    public function handle(): void
    {
        // Booking yang departure_at sudah lewat tapi masih pending/in_review
        $staleBookings = Booking::whereIn('status', ['pending', 'in_review'])
            ->where('departure_at', '<', now())
            ->get();

        if ($staleBookings->isEmpty()) {
            $this->info('Tidak ada booking yang perlu di-expire.');
            return;
        }

        foreach ($staleBookings as $booking) {
            $booking->update([
                'status'               => 'cancelled',
                'cancellation_reason'  => 'Otomatis dibatalkan sistem — booking tidak disetujui sebelum tanggal keberangkatan.',
            ]);

            // Bebaskan kendaraan & driver
            $booking->vehicle()->first()?->update(['status' => 'available']);
            $booking->driver()->first()?->update(['status' => 'available']);

            $this->activityLogService->logAction(
                action: 'auto_expired',
                description: "Booking {$booking->booking_code} otomatis dibatalkan karena melewati tanggal keberangkatan.",
            );

            $this->line("Expired: {$booking->booking_code} (departure: {$booking->departure_at})");
        }

        $this->info("Total {$staleBookings->count()} booking di-expire.");
    }
}
