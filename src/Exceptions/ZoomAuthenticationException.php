<?php

declare(strict_types=1);

namespace Aboutnima\LaravelZoom\Exceptions;

final class ZoomAuthenticationException extends ZoomException
{
    public static function tokenRequestFailed(string $message): self
    {
        return new self("Failed to request Zoom access token: {$message}");
    }

    public static function unauthenticated(string $message): self
    {
        return new self("Unauthorized: Zoom API token is invalid or expired and refresh failed: {$message}");
    }
}
