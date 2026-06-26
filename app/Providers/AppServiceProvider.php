<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $isLocal = isset($_SERVER['HTTP_HOST']) && (
            str_contains($_SERVER['HTTP_HOST'], 'localhost') || 
            str_contains($_SERVER['HTTP_HOST'], '127.0.0.1') || 
            str_contains($_SERVER['HTTP_HOST'], '192.168.')
        );

        if (!$isLocal) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
    }
}
