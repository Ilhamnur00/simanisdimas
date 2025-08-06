<?php 

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordBase;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends ResetPasswordBase
{
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Permintaan Reset Kata Sandi')
            ->line('Anda menerima email ini karena kami menerima permintaan reset kata sandi untuk akun Anda.')
            ->action('Reset Kata Sandi Sekarang', url(config('app.url').route('password.reset', $this->token, false)))
            ->line('Jika Anda tidak meminta reset, abaikan email ini.');
    }
}
