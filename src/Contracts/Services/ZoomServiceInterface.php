<?php
declare(strict_types=1);

namespace Aboutnima\LaravelZoom\Contracts\Services;

use Carbon\Carbon;

interface ZoomServiceInterface
{
    /**
     * ZoomService constructor.
     */
    public function __construct(
        string $baseUrl,
        string $accountId,
        string $clientId,
        string $clientSecret
    );

    /**
     * Get the current access token.
     */
    public function getAccessToken(): string;

    /**
     * Get the token type (e.g., "bearer").
     */
    public function getTokenType(): string;

    /**
     * Get the expiration date and time of the token.
     */
    public function getExpiresAt(): Carbon;

    /**
     * Get how long (in seconds as float) the token is valid for.
     */
    public function getExpiresIn(): float;

    /**
     * Get the authorized scopes for the token.
     */
    public function getScope(): string;

    /**
     * Get the base API URL for Zoom.
     */
    public function getApiUrl(): string;
}
