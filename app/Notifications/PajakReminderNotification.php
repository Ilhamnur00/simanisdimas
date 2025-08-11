<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Carbon\Carbon;
use App\Models\Kendaraan;

class PajakReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Kendaraan $kendaraan,
        public string $tipe
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

public function toMail(object $notifiable): MailMessage
{
    $tanggal = $this->kendaraan->tanggal_pajak
        ->locale('id')
        ->translatedFormat('d F Y');

    $namaKendaraan = $this->kendaraan->nama ?? 'kendaraan Anda';

    $pesan = $this->tipe === 'H-7'
        ? "Pajak kendaraan *{$namaKendaraan}* akan jatuh tempo pada {$tanggal}. Segera lakukan pembayaran untuk menghindari denda."
        : "Hari ini adalah batas akhir pembayaran pajak kendaraan *{$namaKendaraan}*.";

    return (new MailMessage)
        ->subject("Pengingat Pajak Kendaraan ({$this->tipe})")
        ->greeting("Halo {$notifiable->name},")
        ->line($pesan)
        ->action('Lihat Detail Kendaraan', route('kendaraan.index', $this->kendaraan->id))
        ->line('Terima kasih telah menggunakan SIMANIS.')
        ->salutation('Hormat kami, Tim SIMANIS');
}


}
