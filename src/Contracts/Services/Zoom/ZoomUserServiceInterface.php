<?php

declare(strict_types=1);

namespace Aboutnima\LaravelZoom\Contracts\Services\Zoom;

use Illuminate\Http\Client\Response;

interface ZoomUserServiceInterface
{
    /**
     * Get all users
     */
    public function getUsers(): Response;

    /**
     * Get a user by id
     */
    public function getUser(string $id): Response;
}
