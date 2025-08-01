<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
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
        TransaksiBarang::observe(TransaksiBarangObserver::class);
        User::observe(UserObserver::class);
    }
}
