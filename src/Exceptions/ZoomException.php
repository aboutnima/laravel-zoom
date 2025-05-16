<?php

declare(strict_types=1);

namespace Aboutnima\LaravelZoom\Exceptions;

use RuntimeException;

final class ZoomException extends RuntimeException
{
    public static function failed(string $message): self
    {
        return new self("Zoom request failed: {$message}");
    }
}
