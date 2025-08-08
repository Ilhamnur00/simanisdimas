<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Scheduling\Schedule;
use App\Models\TransaksiBarang;
use App\Observers\TransaksiBarangObserver;
use App\Models\User;
use App\Observers\UserObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register observers
        TransaksiBarang::observe(TransaksiBarangObserver::class);
        User::observe(UserObserver::class);

        // Register scheduler for pajak:reminder command
        $this->app->booted(function () {
            $schedule = $this->app->make(Schedule::class);
            $schedule->command('pajak:reminder')->daily();
        });
    }
}