<?php
// app/Notifications/BookingApprovedNotification.php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class BookingApprovedNotification extends Notification
{
    public function __construct(public Booking $booking) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Booking {$this->booking->booking_code} Disetujui")
            ->greeting("Yth. {$notifiable->name},")
            ->line("Pemesanan kendaraan Anda telah disetujui oleh semua pihak.")
            ->line("**Kode Booking:** {$this->booking->booking_code}")
            ->line("**Kendaraan:** {$this->booking->vehicle->brand} {$this->booking->vehicle->model} ({$this->booking->vehicle->plate_number})")
            ->line("**Driver:** {$this->booking->driver->user->name}")
            ->line("**Tanggal Berangkat:** {$this->booking->departure_at->format('d M Y H:i')}")
            ->action('Lihat Detail', route('admin.bookings.show', $this->booking))
            ->line('Selamat bertugas!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'booking_id'   => $this->booking->id,
            'booking_code' => $this->booking->booking_code,
            'message'      => "Booking {$this->booking->booking_code} Anda telah disetujui.",
            'url'          => route('admin.bookings.show', $this->booking),
        ];
    }
}
