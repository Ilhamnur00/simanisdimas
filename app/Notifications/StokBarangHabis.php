<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Barang;


class StokBarangHabis extends Notification
{
    protected $barang;

    public function __construct(Barang $barang)
    {
        $this->barang = $barang;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('📦 Stok Barang Menipis / Habis')
            ->line("Stok barang *{$this->barang->nama_barang}* sekarang tersisa {$this->barang->stok} unit.")
            ->line('Segera lakukan pengadaan ulang jika diperlukan.')
            ->action('Lihat Barang', url('/admin/barang'))
            ->line('Pesan ini dikirim otomatis oleh sistem.');
    }
}
