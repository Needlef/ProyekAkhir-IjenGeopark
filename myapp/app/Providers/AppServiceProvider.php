<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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
        // Force HTTPS if running behind Cloudflare Tunnel (trycloudflare) or other proxies
        if (request()->header('x-forwarded-proto') === 'https' || str_contains(request()->getHost(), 'trycloudflare.com')) {
            URL::forceScheme('https');
        }
    }
}
