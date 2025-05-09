<?php
declare(strict_types=1);

namespace Aboutnima\LaravelZoom\Services\Zoom;

use Aboutnima\LaravelZoom\Services\ZoomService;

final readonly class ZoomUserService {
    public function __construct(
        private ZoomService $zoomService,
    )
    {
    }

    public function getUsers(): array
    {
        return $this->zoomService->sendRequest(
            'get',
            "users",
        );
    }
}
