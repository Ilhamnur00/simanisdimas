<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Kendaraan;
use App\Notifications\PajakReminderNotification;
use Illuminate\Support\Facades\Log;

class SendPajakReminder extends Command
{
    protected $signature = 'pajak:reminder';
    protected $description = 'Kirim email pengingat pajak kendaraan H-7 dan H-0';

    public function handle()
    {
        $this->sendReminder(7, 'reminder_h7_sent'); // H-7
        $this->sendReminder(0, 'reminder_h0_sent'); // H-0

        $this->info('Reminder pajak kendaraan selesai dikirim.');
    }

    private function sendReminder(int $daysBefore, string $column)
    {
        $targetDate = now()->startOfDay()->addDays($daysBefore);

        $kendaraans = Kendaraan::whereNotNull('tanggal_pajak')
            ->whereDate('tanggal_pajak', $targetDate)
            ->where($column, false)
            ->with('user')
            ->get();

        foreach ($kendaraans as $kendaraan) {
            if ($kendaraan->user) {
                $kendaraan->user->notify(
                    new PajakReminderNotification($kendaraan, "H-{$daysBefore}")
                );

                $kendaraan->update([$column => true]);

                Log::info("Reminder pajak dikirim", [
                    'user_email' => $kendaraan->user->email,
                    'kendaraan_id' => $kendaraan->id,
                    'days_before' => $daysBefore,
                    'tanggal_pajak' => $kendaraan->tanggal_pajak,
                ]);
            }
        }
    }
}
