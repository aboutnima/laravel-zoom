<?php

declare(strict_types=1);

namespace Aboutnima\LaravelZoom\Contracts\Services;

use Aboutnima\LaravelZoom\Auth\ZoomTokenManager;
use Aboutnima\LaravelZoom\Services\Zoom\ZoomRoomService;
use Aboutnima\LaravelZoom\Services\Zoom\ZoomUserService;
use Illuminate\Http\Client\Response;

interface ZoomServiceInterface
{
    /**
     * Get ZoomTokenManager.
     */
    public function tokenManager(): ZoomTokenManager;

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
     * Get the ZoomUserService.
     */
    public function userService(): ZoomUserService;

    /**
     * Get the ZoomRoomService.
     */
    public function roomService(): ZoomRoomService;
}
