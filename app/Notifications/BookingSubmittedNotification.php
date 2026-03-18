<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class BookingSubmittedNotification extends Notification
{
    public function __construct(
        public Booking $booking,
        public int $level,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Persetujuan Booking {$this->booking->booking_code} - Level {$this->level}")
            ->greeting("Yth. {$notifiable->name},")
            ->line("Ada pemesanan kendaraan baru yang membutuhkan persetujuan Anda (Level {$this->level}).")
            ->line("**Kode Booking:** {$this->booking->booking_code}")
            ->line("**Pemohon:** {$this->booking->requester->name}")
            ->line("**Tujuan:** {$this->booking->purpose}")
            ->line("**Destinasi:** {$this->booking->destination}")
            ->line("**Tanggal:** {$this->booking->departure_at->format('d M Y H:i')}")
            ->action('Proses Persetujuan', route('approvals.show', $this->booking))
            ->line('Terima kasih.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'booking_id'   => $this->booking->id,
            'booking_code' => $this->booking->booking_code,
            'message'      => "Booking {$this->booking->booking_code} menunggu persetujuan Anda (Level {$this->level}).",
            'url'          => route('approvals.show', $this->booking),
        ];
    }
}
