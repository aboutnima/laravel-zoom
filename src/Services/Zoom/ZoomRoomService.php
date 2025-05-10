<?php

declare(strict_types=1);

namespace Aboutnima\LaravelZoom\Services\Zoom;

use Aboutnima\LaravelZoom\Contracts\Services\Zoom\ZoomRoomServiceInterface;
use Aboutnima\LaravelZoom\Services\ZoomService;

final readonly class ZoomRoomService implements ZoomRoomServiceInterface
{
    public function __construct(
        private ZoomService $zoomService,
    ) {
    }

    public function getRooms(): array
    {
        return $this->zoomService->sendRequest(
            'get',
            'rooms',
        );
    }

    public function getRoom(string $id): array
    {
        return $this->zoomService->sendRequest(
            'get',
            "rooms/{$id}",
        );
    }
}
