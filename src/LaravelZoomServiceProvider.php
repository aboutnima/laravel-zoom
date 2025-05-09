<?php

namespace Aboutnima\LaravelZoom;

use Aboutnima\LaravelZoom\Services\ZoomService;
use Illuminate\Support\ServiceProvider;

class LaravelZoomServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Merge the configuration file
        $this->mergeConfigFrom(__DIR__.'/../config/zoom.php', 'zoom');

        // Register the ZoomService singleton
        $this->app->singleton('zoom', fn ($app): ZoomService => new ZoomService(
            config('zoom.base_url'),
            config('zoom.account_id'),
            config('zoom.client_id'),
            config('zoom.client_secret'),
        ));
    }

    public function boot(): void
    {
        // Publish the configuration file
        $this->publishes([
            __DIR__.'/../config/zoom.php' => config_path('zoom.php'),
        ]);
    }
}
