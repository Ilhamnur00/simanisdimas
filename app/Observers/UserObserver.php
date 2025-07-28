<?php

namespace App\Observers;

use App\Models\User;
use App\Notifications\AkunBaruDibuat;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class UserObserver
{
    public function creating(User $user): void
    {
        if (empty($user->password)) {
            $randomPassword = Str::random(10);
            $user->password = bcrypt($randomPassword);

            session(['__generated_password' => $randomPassword]);
        }
    }

    public function created(User $user): void
    {
        $plainPassword = session()->pull('__generated_password');

        if ($plainPassword) {
            $user->notify(new AkunBaruDibuat($plainPassword));
            Log::info('✅ Notifikasi dikirim ke ' . $user->email);
        } else {
            Log::warning('⚠️ Tidak ada password plain untuk ' . $user->email);
        }
    }
}
