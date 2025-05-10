<?php

declare(strict_types=1);

namespace Aboutnima\LaravelZoom\Contracts\Services\Zoom;

interface ZoomRoomServiceInterface
{
    /**
     * Get all users
     */
    public function getRooms(): array;

    /**
     * Get a user by id
     */
    public function getRoom(string $id): array;
}
