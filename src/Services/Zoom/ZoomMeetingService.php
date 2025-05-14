<?php

declare(strict_types=1);

namespace Aboutnima\LaravelZoom\Services\Zoom;

use Aboutnima\LaravelZoom\Contracts\Services\Zoom\ZoomMeetingServiceInterface;
use Aboutnima\LaravelZoom\Services\ZoomService;
use Illuminate\Http\Client\Response;

final readonly class ZoomMeetingService implements ZoomMeetingServiceInterface
{
    public function __construct(
        private ZoomService $zoomService,
    ) {}

    public function getMeetings(): Response
    {
        return $this->zoomService->sendRequest(
            'get',
            'mettings',
        );
    }
}
