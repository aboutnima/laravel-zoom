<?php

declare(strict_types=1);

namespace Aboutnima\LaravelZoom\Contracts\Auth;

use Carbon\Carbon;

interface ZoomTokenManagerInterface
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
     * Get cache name
     */
    public function getCacheKey(): string;

    /**
     * Clear token and forget cache
     */
    public function clear(): void;

    /**
     * Check if client is authenticated
     */
    public function isAuthenticated(): bool;

    /**
     * Get the base URL for the Zoom API.
     */
    public function getBaseUrl(): string;

    /**
     * Get the account ID.
     */
    public function getAccountId(): string;

    /**
     * Get the client ID.
     */
    public function getClientId(): string;

    /**
     * Get the client secret.
     */
    public function getClientSecret(): string;

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
    public function getExpiresAt(): ?Carbon;

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
