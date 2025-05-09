<?php
declare(strict_types=1);

namespace Aboutnima\LaravelZoom\Contracts\Services\Zoom;

interface ZoomUserServiceInterface
{
    /**
     * Get all users
     */
    public function getUsers(): array;

    /**
     * Get a user by id
     */
    public function getUser(string $id): array;
}
