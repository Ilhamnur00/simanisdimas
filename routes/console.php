<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Console\Commands\SendPajakReminder;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('pajak:reminder', function () {
    $this->info('Memanggil SendPajakReminder...');
    app(SendPajakReminder::class)->handle();
})->describe('Kirim pengingat pajak kendaraan');