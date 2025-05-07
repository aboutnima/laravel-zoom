<?php

namespace Aboutnima\LaravelZoom\Contracts\Services;

interface ZoomServiceInterface
{
    /**
     * ZoomService constructor.
     */
    public function __construct(string $baseUrl, string $redirectUrl, string $id, string $secret);

    /**
     * Check if the user is authorized to use the Zoom API.
     */
    public function isAuthorized(): bool;
}
