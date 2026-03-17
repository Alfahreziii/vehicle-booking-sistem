<?php
// app/Services/ApprovalService.php

namespace App\Services;

use App\Models\Booking;
use App\Models\BookingApproval;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ApprovalService
{
    public function __construct(
        protected NotificationService $notificationService,
        protected ActivityLogService $activityLogService,
    ) {}

    /**
     * Proses persetujuan oleh approver
     */
    public function approve(Booking $booking, string $notes = ''): Booking
    {
        return DB::transaction(function () use ($booking, $notes) {

            $currentLevel = $booking->current_approval_level + 1;

            // Ambil approval record untuk level ini
            $approval = BookingApproval::where('booking_id', $booking->id)
                ->where('approver_id', Auth::id())
                ->where('level', $currentLevel)
                ->where('status', 'waiting')
                ->firstOrFail();

            // Update approval record
            $approval->update([
                'status'   => 'approved',
                'notes'    => $notes,
                'acted_at' => now(),
            ]);

            // Naikkan level di booking
            $booking->increment('current_approval_level');
            $booking->refresh();

            // Cek apakah ini level terakhir
            if ($booking->current_approval_level >= $booking->total_approver_levels) {
                // Semua level sudah approve → booking disetujui penuh
                $booking->update(['status' => 'approved']);

                // Kirim notifikasi ke pemohon
                $this->notificationService->sendApprovedToRequester($booking);

                $this->activityLogService->log(
                    action: 'fully_approved',
                    subject: $booking,
                    description: "Booking {$booking->booking_code} disetujui penuh oleh semua level.",
                );

                Log::info("Booking fully approved: {$booking->booking_code}");
            } else {
                // Masih ada level berikutnya → kirim notif ke approver selanjutnya
                $nextApproval = BookingApproval::where('booking_id', $booking->id)
                    ->where('level', $booking->current_approval_level + 1)
                    ->first();

                if ($nextApproval) {
                    $this->notificationService->sendToApprover($booking, $nextApproval);
                }

                $this->activityLogService->log(
                    action: 'approved',
                    subject: $booking,
                    description: "Booking {$booking->booking_code} disetujui level {$currentLevel} oleh " . Auth::user()->name,
                );
            }

            return $booking->fresh(['approvals', 'vehicle', 'driver']);
        });
    }

    /**
     * Proses penolakan oleh approver
     */
    public function reject(Booking $booking, string $notes): Booking
    {
        return DB::transaction(function () use ($booking, $notes) {

            $currentLevel = $booking->current_approval_level + 1;

            // Ambil approval record untuk level ini
            $approval = BookingApproval::where('booking_id', $booking->id)
                ->where('approver_id', Auth::id())
                ->where('level', $currentLevel)
                ->where('status', 'waiting')
                ->firstOrFail();

            // Update approval record
            $approval->update([
                'status'   => 'rejected',
                'notes'    => $notes,
                'acted_at' => now(),
            ]);

            // Update status booking
            $booking->update(['status' => 'rejected']);

            // Bebaskan kendaraan & driver kembali
            $booking->vehicle()->first()?->update(['status' => 'available']);
            $booking->driver()->first()?->update(['status' => 'available']);

            // Kirim notifikasi ke pemohon
            $this->notificationService->sendRejectedToRequester($booking, $notes);

            $this->activityLogService->log(
                action: 'rejected',
                subject: $booking,
                old: ['status' => 'in_review'],
                new: ['status' => 'rejected', 'notes' => $notes],
                description: "Booking {$booking->booking_code} ditolak level {$currentLevel} oleh " . Auth::user()->name . ". Alasan: {$notes}",
            );

            Log::info("Booking rejected: {$booking->booking_code}", [
                'rejected_by' => Auth::id(),
                'level'       => $currentLevel,
                'notes'       => $notes,
            ]);

            return $booking->fresh(['approvals', 'vehicle', 'driver']);
        });
    }

    /**
     * Cek apakah user ini berhak memproses approval booking
     */
    public function canProcess(Booking $booking): bool
    {
        if (!in_array($booking->status, ['in_review', 'pending'])) {
            return false;
        }

        $nextLevel = $booking->current_approval_level + 1;

        return BookingApproval::where('booking_id', $booking->id)
            ->where('approver_id', Auth::id())
            ->where('level', $nextLevel)
            ->where('status', 'waiting')
            ->exists();
    }
}
