<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Middleware\Authenticate;

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
        Authenticate::redirectUsing(function ($request) {
            notify()->error('Silakan login terlebih dahulu ⚠️', 'Akses Ditolak');
            return route('login');
        });
    }
}
