<?php

namespace Aboutnima\LaravelZoom;

use Aboutnima\LaravelZoom\Services\ZoomService;
use Illuminate\Support\ServiceProvider;

class LaravelZoomServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton('zoom', fn ($app): ZoomService => new ZoomService(
            config('zoom.base_url'),
            config('zoom.client_id'),
            config('zoom.client_secret'),
            config('zoom.redirect_url')
        ));
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/zoom.php' => config_path('zoom.php'),
        ]);
    }
}
