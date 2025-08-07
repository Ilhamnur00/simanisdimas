<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class LaporanNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $user;
    protected $pdfData;
    protected $jenisLaporan;

    public function __construct($user, $pdfData, $jenisLaporan = 'transaksi')
    {
        $this->user = $user;
        $this->pdfData = $pdfData;
        $this->jenisLaporan = $jenisLaporan;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $laporanInfo = [
            'transaksi' => [
                'subject' => 'Laporan Transaksi Barang',
                'fileName' => 'laporan-transaksi.pdf',
                'line' => 'Berikut adalah laporan transaksi barang Anda.'
            ],
            'perawatan' => [
                'subject' => 'Laporan Perawatan Device',
                'fileName' => 'laporan-perawatan-device.pdf',
                'line' => 'Berikut adalah laporan perawatan device Anda.'
            ],
            'perawatan_kendaraan' => [
                'subject' => 'Laporan Perawatan Kendaraan',
                'fileName' => 'laporan-perawatan-kendaraan.pdf',
                'line' => 'Berikut adalah laporan perawatan kendaraan Anda.'
            ],
            'pajak_kendaraan' => [
                'subject' => 'Laporan Pajak Kendaraan',
                'fileName' => 'laporan-pajak-kendaraan.pdf',
                'line' => 'Berikut adalah laporan pajak kendaraan Anda.'
            ],
        ];

        $info = $laporanInfo[$this->jenisLaporan] ?? $laporanInfo['transaksi'];

        return (new MailMessage)
            ->subject($info['subject'])
            ->greeting('Hai, ' . $this->user->name)
            ->line($info['line'])
            ->attachData($this->pdfData, $info['fileName'], [
                'mime' => 'application/pdf',
            ])
            ->line('Terima kasih telah menggunakan aplikasi kami.');
    }
}
