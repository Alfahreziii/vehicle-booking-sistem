<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class BookingRejectedNotification extends Notification
{
    public function __construct(
        public Booking $booking,
        public string $notes,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Booking {$this->booking->booking_code} Ditolak")
            ->greeting("Yth. {$notifiable->name},")
            ->line("Mohon maaf, pemesanan kendaraan Anda ditolak.")
            ->line("**Kode Booking:** {$this->booking->booking_code}")
            ->line("**Alasan:** {$this->notes}")
            ->action('Lihat Detail', route('admin.bookings.show', $this->booking))
            ->line('Silakan buat pemesanan baru jika diperlukan.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'booking_id'   => $this->booking->id,
            'booking_code' => $this->booking->booking_code,
            'message'      => "Booking {$this->booking->booking_code} Anda ditolak. Alasan: {$this->notes}",
            'url'          => route('admin.bookings.show', $this->booking),
        ];
    }
}
