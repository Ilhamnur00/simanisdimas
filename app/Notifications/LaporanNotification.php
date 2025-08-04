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

    public function __construct($user, $pdfPath)
    {
        $this->user = $user;
        $this->pdfPath = $pdfPath; // ⬅️ gunakan nama yang konsisten
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Laporan Transaksi Barang')
            ->greeting('Hai, ' . $this->user->name)
            ->line('Berikut adalah laporan transaksi Anda.')
            ->attachData($this->pdfPath, 'laporan-transaksi.pdf', [
                'mime' => 'application/pdf',
            ])
            ->line('Terima kasih telah menggunakan aplikasi kami.');
    }
}
