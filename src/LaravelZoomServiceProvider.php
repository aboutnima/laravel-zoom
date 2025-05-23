<?php

namespace Aboutnima\LaravelZoom;

use Aboutnima\LaravelZoom\Auth\ZoomTokenManager;
use Aboutnima\LaravelZoom\Services\ZoomService;
use Illuminate\Support\ServiceProvider;

class LaravelZoomServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Merge the configuration file
        $this->mergeConfigFrom(__DIR__.'/../config/zoom.php', 'zoom');

        // Register the ZoomTokenManager singleton
        $this->app->singleton(ZoomTokenManager::class, fn (): ZoomTokenManager => new ZoomTokenManager(
            'https://zoom.us/oauth/token',
            config('zoom.account_id'),
            config('zoom.client_id'),
            config('zoom.client_secret'),
        ));

        // Register the ZoomService singleton and define TokenManager class with data
        $this->app->singleton('zoom', fn (): ZoomService => new ZoomService($this->app->make(ZoomTokenManager::class)));
    }

    public function boot(): void
    {
        // Publish the configuration file
        $this->publishes([
            __DIR__.'/../config/zoom.php' => config_path('zoom.php'),
        ]);
    }
}
