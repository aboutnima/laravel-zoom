<?php

declare(strict_types=1);

namespace Aboutnima\LaravelZoom\Services\Zoom;

use Aboutnima\LaravelZoom\Contracts\Services\Zoom\ZoomUserServiceInterface;
use Aboutnima\LaravelZoom\Services\ZoomService;
use Illuminate\Http\Client\Response;

final readonly class ZoomUserService implements ZoomUserServiceInterface
{
    public function __construct(
        private ZoomService $zoomService,
    ) {}

    public function getUsers(): Response
    {
        return $this->zoomService->sendRequest(
            'get',
            'users',
        );
    }

    public function getUser(string $id): Response
    {
        return $this->zoomService->sendRequest(
            'get',
            "users/{$id}",
        );
    }
}
