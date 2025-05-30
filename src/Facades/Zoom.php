<?php

namespace Aboutnima\LaravelZoom\Facades;

use Illuminate\Support\Facades\Facade;

final class Zoom extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'zoom';
    }
}
