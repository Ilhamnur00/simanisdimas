<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Contracts\Queue\ShouldQueue;

class AkunBaruDibuat extends Notification implements ShouldQueue

{
    use Queueable;

    protected $password;

    public function __construct($password)
    {
        $this->password = $password;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Akun SIMANISDIMAS Anda Telah Dibuat')
            ->greeting('Halo, ' . $notifiable->name)
            ->line('Akun Anda telah berhasil dibuat oleh admin.')
            ->line('Berikut detail login Anda:')
            ->line('Email: ' . $notifiable->email)
            ->line('Password: ' . $this->password)
            ->line('Silakan login dan segera ganti password Anda.')
            ->salutation('Terima kasih.');
    }
}
