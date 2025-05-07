<?php

namespace Aboutnima\LaravelZoom\Services;

use Aboutnima\LaravelZoom\Contracts\Services\ZoomServiceInterface;

final readonly class ZoomService implements ZoomServiceInterface
{
    public function __construct(string $baseUrl, string $redirectUrl, string $id, string $secret)
    {
        return $this;
    }

    public function isAuthorized(): bool
    {
        return false;
    }
}
