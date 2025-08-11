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
    protected $pdfBase64;
    protected $jenisLaporan;

    public function __construct($user, string $pdfBase64, $jenisLaporan = 'transaksi')
    {
        $this->user = $user;
        $this->pdfBase64 = $pdfBase64;
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

        // Decode dari Base64 ke binary sebelum attach
        $pdfBinary = base64_decode($this->pdfBase64);

        return (new MailMessage)
            ->subject($info['subject'])
            ->greeting('Hai, ' . $this->user->name)
            ->line($info['line'])
            ->attachData($pdfBinary, $info['fileName'], [
                'mime' => 'application/pdf',
            ])
            ->line('Terima kasih telah menggunakan aplikasi kami.');
    }
}
