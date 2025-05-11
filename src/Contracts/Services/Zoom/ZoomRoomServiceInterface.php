<?php

declare(strict_types=1);

namespace Aboutnima\LaravelZoom\Contracts\Services\Zoom;

use Illuminate\Http\Client\Response;

interface ZoomRoomServiceInterface
{
    /**
     * Get all users
     */
    public function getRooms(): Response;

    /**
     * Get a user by id
     */
    public function getRoom(string $id): Response;
}
