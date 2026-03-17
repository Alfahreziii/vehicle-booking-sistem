<?php
// app/Services/NotificationService.php

namespace App\Services;

use App\Models\Booking;
use App\Models\BookingApproval;
use App\Notifications\BookingSubmittedNotification;
use App\Notifications\BookingApprovedNotification;
use App\Notifications\BookingRejectedNotification;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    public function sendToApprover(Booking $booking, BookingApproval $approval): void
    {
        try {
            $approval->approver->notify(new BookingSubmittedNotification($booking, $approval->level));
        } catch (\Exception $e) {
            Log::error("Failed to send notification: " . $e->getMessage());
        }
    }

    public function sendApprovedToRequester(Booking $booking): void
    {
        try {
            $booking->requester->notify(new BookingApprovedNotification($booking));
        } catch (\Exception $e) {
            Log::error("Failed to send approved notification: " . $e->getMessage());
        }
    }

    public function sendRejectedToRequester(Booking $booking, string $notes): void
    {
        try {
            $booking->requester->notify(new BookingRejectedNotification($booking, $notes));
        } catch (\Exception $e) {
            Log::error("Failed to send rejected notification: " . $e->getMessage());
        }
    }
}
