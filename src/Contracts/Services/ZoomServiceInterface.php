<?php

declare(strict_types=1);

namespace Aboutnima\LaravelZoom\Contracts\Services;

use Aboutnima\LaravelZoom\Services\Zoom\ZoomRoomService;
use Aboutnima\LaravelZoom\Services\Zoom\ZoomUserService;
use Illuminate\Http\Client\Response;

interface ZoomServiceInterface
{
    /**
     * Send a request to the Zoom API.
     */
    public function sendRequest(
        string $method,
        string $endpoint,
        array $query = [],
        array $payload = []
    ): Response;

    /**
     * Get the Zoom user service.
     */
    public function userService(): ZoomUserService;

    /**
     * Get the Zoom room service.
     */
    public function roomService(): ZoomRoomService;
}
