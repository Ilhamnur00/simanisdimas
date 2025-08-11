<?php

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;

return function (Application $app, Schedule $schedule) {

    $schedule->command('pajak:reminder')
        ->dailyAt('08:00')
        ->before(function () {
            \Illuminate\Support\Facades\Log::channel('scheduler')->info('Memulai pengiriman reminder pajak harian');
        })
        ->after(function () {
            \Illuminate\Support\Facades\Log::channel('scheduler')->info('Pengiriman reminder pajak harian selesai');
        })
        ->onFailure(function (\Throwable $e) {
            \Illuminate\Support\Facades\Log::channel('scheduler')->error('Gagal mengirim reminder: ' . $e->getMessage());
        });
};