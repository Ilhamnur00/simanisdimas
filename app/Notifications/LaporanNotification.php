<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class LaporanNotification extends Notification
{
    protected $user;
    protected $pdfPath;
    protected $jenisLaporan;

    public function __construct($user, $pdfPath, $jenisLaporan = 'transaksi')
    {
        $this->user = $user;
        $this->pdfPath = $pdfPath;
        $this->jenisLaporan = $jenisLaporan;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $subject = $this->jenisLaporan === 'perawatan-device'
            ? 'Laporan Perawatan Device'
            : 'Laporan Transaksi Barang';

        $fileName = $this->jenisLaporan === 'perawatan-device'
            ? 'laporan-perawatan-device.pdf'
            : 'laporan-transaksi.pdf';

        $line = $this->jenisLaporan === 'perawatan-device'
            ? 'Berikut adalah laporan perawatan device Anda.'
            : 'Berikut adalah laporan transaksi Anda.';

        return (new MailMessage)
            ->subject($subject)
            ->greeting('Hai, ' . $this->user->name)
            ->line($line)
            ->attachData($this->pdfPath, $fileName, [
                'mime' => 'application/pdf',
            ])
            ->line('Terima kasih telah menggunakan aplikasi kami.');
    }
}
