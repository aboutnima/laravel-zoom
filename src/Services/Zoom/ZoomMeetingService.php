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

    public function getMeetings(string $userId = 'me'): Response
    {
        return $this->zoomService->sendRequest(
            'get',
            "users/{$userId}/meetings",
        );
    }

    public function getMeeting(string $meetingId): Response
    {
        return $this->zoomService->sendRequest(
            'get',
            "meetings/{$meetingId}",
        );
    }

    public function createMeeting(array $payload, string $userId = 'me'): Response
    {
        return $this->zoomService->sendRequest(
            method: 'post',
            endpoint: "users/{$userId}/meetings",
            payload: $payload,
        );
    }
}
