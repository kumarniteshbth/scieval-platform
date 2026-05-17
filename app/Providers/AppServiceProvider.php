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
        // Force HTTPS for all generated URLs when deployed behind Render's
        // reverse proxy (which terminates SSL/TLS before reaching the container).
        // Using $_SERVER superglobals avoids config-cache stale values.
        $isHttps = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
            || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
            || str_starts_with(config('app.url', ''), 'https://')
            || $this->app->environment('production');

        if ($isHttps) {
            URL::forceScheme('https');
        }
    }
}
