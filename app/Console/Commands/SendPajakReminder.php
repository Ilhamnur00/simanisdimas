<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Kendaraan;
use App\Notifications\PajakReminderNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class SendPajakReminder extends Command
{
    protected $signature = 'pajak:reminder';
    protected $description = 'Kirim email pengingat pajak kendaraan H-7 dan H-0';

    public function handle()
    {
        // Cek jika sudah dijalankan hari ini
        $cacheKey = 'pajak_reminder_' . now()->toDateString();
        if (Cache::has($cacheKey)) {
            Log::channel('scheduler')->info('Reminder hari ini sudah dikirim sebelumnya');
            return;
        }

        Log::channel('scheduler')->info('Memulai proses pengiriman reminder pajak', [
            'memory_usage' => round(memory_get_usage(true) / 1024 / 1024, 2) . ' MB'
        ]);

        try {
            $this->sendReminder(7, 'reminder_h7_sent');
            $this->sendReminder(0, 'reminder_h0_sent');
            
            Cache::put($cacheKey, true, now()->addDay()); // Tandai sudah dikirim
            Log::channel('scheduler')->info('Semua reminder berhasil dikirim');
        } catch (\Throwable $e) {
            Log::channel('scheduler')->error('Gagal mengirim reminder', [
                'error' => $e->getMessage(),
                'trace' => config('app.debug') ? $e->getTraceAsString() : 'hidden'
            ]);
        }
    }

    private function sendReminder(int $daysBefore, string $column)
    {
        $startDate = now()->startOfDay()->addDays($daysBefore);
        $endDate = $startDate->copy()->endOfDay();

        $kendaraans = Kendaraan::whereNotNull('tanggal_pajak')
            ->whereBetween('tanggal_pajak', [$startDate, $endDate])
            ->where($column, false)
            ->with('user')
            ->get();

        Log::channel('scheduler')->info("Memproses H-{$daysBefore}", [
            'target_date' => $startDate->toDateString(),
            'jumlah_kendaraan' => $kendaraans->count()
        ]);

        $successCount = 0;
        $failedCount = 0;

        foreach ($kendaraans as $kendaraan) {
            try {
                if (!$kendaraan->user) {
                    Log::channel('scheduler')->warning('Kendaraan tanpa user', [
                        'kendaraan_id' => $kendaraan->id
                    ]);
                    continue;
                }

                $kendaraan->user->notify(new PajakReminderNotification(
                    $kendaraan, 
                    "H-{$daysBefore}"
                ));
                
                $kendaraan->update([$column => true]);
                $successCount++;

                Log::channel('scheduler')->debug('Notifikasi terkirim', [
                    'user' => $kendaraan->user->email,
                    'kendaraan' => $kendaraan->id
                ]);
            } catch (\Exception $e) {
                $failedCount++;
                Log::channel('scheduler')->error('Gagal mengirim notifikasi', [
                    'kendaraan_id' => $kendaraan->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        Log::channel('scheduler')->info("Hasil H-{$daysBefore}", [
            'sukses' => $successCount,
            'gagal' => $failedCount
        ]);
    }
}