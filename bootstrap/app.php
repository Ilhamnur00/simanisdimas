<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->withSchedule(function (\Illuminate\Console\Scheduling\Schedule $schedule) {
        // Jadwalkan command pajak:reminder
        $schedule->command('pajak:reminder')
            ->everyMinute()
            ->before(function () {
                \Illuminate\Support\Facades\Log::info('Scheduler pajak:reminder dimulai');
            })
            ->after(function () {
                \Illuminate\Support\Facades\Log::info('Scheduler pajak:reminder selesai');
            })
            ->onFailure(function (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error('Error scheduler: ' . $e->getMessage());
            });
    })
    ->create();