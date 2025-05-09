<?php

declare(strict_types=1);

namespace Aboutnima\LaravelZoom\Services\Zoom;

use Aboutnima\LaravelZoom\Contracts\Services\Zoom\ZoomUserServiceInterface;
use Aboutnima\LaravelZoom\Services\ZoomService;

final readonly class ZoomUserService implements ZoomUserServiceInterface
{
    public function __construct(
        private ZoomService $zoomService,
    ) {}

    public function getUsers(): array
    {
        return $this->zoomService->sendRequest(
            'get',
            'users',
        );
    }

    public function getUser(string $id): array
    {
        return $this->zoomService->sendRequest(
            'get',
            "users/{$id}",
        );
    }
}
