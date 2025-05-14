<?php

declare(strict_types=1);

namespace Aboutnima\LaravelZoom\Exceptions;

final class ZoomRequestException extends ZoomException
{
    public static function failed(string $message): self
    {
        return new self("Zoom request failed: {$message}");
    }
}
