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
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('📦 Stok Barang Menipis / Habis')
            ->line("Stok barang *{$this->barang->nama_barang}* sekarang tersisa {$this->barang->stok} unit.")
            ->line('Segera lakukan pengadaan ulang jika diperlukan.')
            ->action('Lihat Barang', url('/admin/barangs/' . $this->barang->id))
            ->line('Pesan ini dikirim otomatis oleh sistem.');
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'Stok Hampir Habis',
            'body' => "Stok barang {$this->barang->nama_barang} tersisa {$this->barang->stok} unit.",
            'icon' => 'heroicon-o-exclamation-circle',
            'url' => url('/admin/barangs/' . $this->barang->id),
            'barang_id' => $this->barang->id,
            'nama_barang' => $this->barang->nama_barang,
            'stok' => $this->barang->stok,
        ];
    }

}
