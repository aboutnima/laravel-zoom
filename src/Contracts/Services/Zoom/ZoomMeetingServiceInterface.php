<?php

declare(strict_types=1);

namespace Aboutnima\LaravelZoom\Contracts\Services\Zoom;

use Illuminate\Http\Client\Response;

interface ZoomMeetingServiceInterface
{
    /**
     * Get all meetings by user id
     */
    public function getMeetings(string $userId = 'me'): Response;

    /**
     * Get a meeting by meeting id
     */
    public function getMeeting(string $meetingId): Response;

    /**
     * Create a meeting for given user
     */
    public function createMeeting(array $payload, string $userId = 'me'): Response;
}
