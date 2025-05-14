<?php

declare(strict_types=1);

namespace Aboutnima\LaravelZoom\Contracts\Services\Zoom;

use Illuminate\Http\Client\Response;

interface ZoomMeetingServiceInterface
{
    /**
     * Get all meetings
     */
    public function getMeetings(): Response;
}
