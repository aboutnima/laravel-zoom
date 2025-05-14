<?php

namespace Aboutnima\LaravelZoom;

use Aboutnima\LaravelZoom\Services\Zoom\ZoomMeetingService;
use Aboutnima\LaravelZoom\Services\Zoom\ZoomRoomService;
use Aboutnima\LaravelZoom\Services\Zoom\ZoomUserService;
use Aboutnima\LaravelZoom\Services\ZoomService;
use Illuminate\Support\ServiceProvider;

class LaravelZoomServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Merge the configuration file
        $this->mergeConfigFrom(__DIR__.'/../config/zoom.php', 'zoom');

        // Register the ZoomService singleton
        $this->app->singleton('zoom', fn (): ZoomService => new ZoomService(
            'https://zoom.us/oauth/token',
            config('zoom.account_id'),
            config('zoom.client_id'),
            config('zoom.client_secret'),
        ));

        // Register the ZoomUserService singleton
        $this->app->singleton(
            ZoomUserService::class,
            fn ($app): ZoomUserService => new ZoomUserService($app->make('zoom'))
        );

        // Register the ZoomRoomService singleton
        $this->app->singleton(
            ZoomRoomService::class,
            fn ($app): ZoomRoomService => new ZoomRoomService($app->make('zoom'))
        );

        // Register the ZoomMeetingService singleton
        $this->app->singleton(
            ZoomMeetingService::class,
            fn ($app): ZoomMeetingService => new ZoomMeetingService($app->make('zoom'))
        );
    }

    public function boot(): void
    {
        // Publish the configuration file
        $this->publishes([
            __DIR__.'/../config/zoom.php' => config_path('zoom.php'),
        ]);
    }
}
