<?php

declare(strict_types=1);

namespace Aboutnima\LaravelZoom\Services\Zoom;

use Aboutnima\LaravelZoom\Contracts\Services\Zoom\ZoomRoomServiceInterface;
use Aboutnima\LaravelZoom\Services\ZoomService;
use Illuminate\Http\Client\Response;

final readonly class ZoomRoomService implements ZoomRoomServiceInterface
{
    public function __construct(
        private ZoomService $zoomService,
    ) {}

    public function getRooms(): Response
    {
        return $this->zoomService->sendRequest(
            'get',
            'rooms',
        );
    }

    public function getRoom(string $id): Response
    {
        return $this->zoomService->sendRequest(
            'get',
            "rooms/{$id}",
        );
    }
}
