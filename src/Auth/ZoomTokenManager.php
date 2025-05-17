<?php

declare(strict_types=1);

namespace Aboutnima\LaravelZoom\Auth;

use Aboutnima\LaravelZoom\Contracts\Auth\ZoomTokenManagerInterface;
use Aboutnima\LaravelZoom\Exceptions\ZoomException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

final class ZoomTokenManager implements ZoomTokenManagerInterface
{
    private const string CACHE_KEY = 'access_token';

    private string $accessToken = '';

    private string $tokenType = '';

    private string $scope = '';

    private string $apiUrl = '';

    private ?Carbon $expiresAt = null;

    public function __construct(
        private readonly string $baseUrl,
        private readonly string $accountId,
        private readonly string $clientId,
        private readonly string $clientSecret,
    ) {
        $this->requestAccessToken();
    }

    public function getCacheKey(): string
    {
        return self::CACHE_KEY;
    }

    public function clear(): void
    {
        Cache::forget(self::CACHE_KEY);

        $this->accessToken = '';
        $this->tokenType = '';
        $this->scope = '';
        $this->apiUrl = '';
        $this->expiresAt = null;
    }

    public function isAuthenticated(): bool
    {
        // Helper closure to handle invalid authentication:
        $invalidateAndReturnFalse = function (): bool {
            $this->clear();

            return false;
        };

        // Retrieve the cached access token data
        $cache = Cache::get(self::CACHE_KEY);

        // Check if the cache is empty or null
        if (blank($cache)) {
            return $invalidateAndReturnFalse();
        }

        // Check if the token has expired
        if (Carbon::parse($cache['expires_at'])->isPast()) {
            return $invalidateAndReturnFalse();
        }

        // Check if the current access token matches the cached one
        if (! isset($cache['access_token']) || $cache['access_token'] !== $this->accessToken) {
            return $invalidateAndReturnFalse();
        }

        return true;
    }

    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    public function getAccountId(): string
    {
        return $this->accountId;
    }

    public function getClientId(): string
    {
        return $this->clientId;
    }

    public function getClientSecret(): string
    {
        return $this->clientSecret;
    }

    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    public function getTokenType(): string
    {
        return $this->tokenType;
    }

    public function getExpiresIn(): float
    {
        return now()->diffInSeconds($this->expiresAt);
    }

    public function getExpiresAt(): ?Carbon
    {
        return $this->expiresAt;
    }

    public function getScope(): string
    {
        return $this->scope;
    }

    public function getApiUrl(): string
    {
        return $this->apiUrl;
    }

    /**
     * Retrieve and cache a new access token if the current one is missing or invalid.
     * If a valid cached token exists, it will be reused.
     */
    private function requestAccessToken(): void
    {
        if (! $this->isAuthenticated()) {
            try {
                $response = Http::asForm()
                    ->withHeaders([
                        'Authorization' => 'Basic '.base64_encode("{$this->clientId}:{$this->clientSecret}"),
                        'Content-Type' => 'application/x-www-form-urlencoded',
                    ])
                    ->post($this->baseUrl, [
                        'grant_type' => 'account_credentials',
                        'account_id' => $this->accountId,
                    ])
                    ->throw();
            } catch (RequestException $e) {
                throw ZoomException::failed($e->getMessage());
            }

            $this->setAccessToken($response->json());
        }
    }

    private function setAccessToken(array $values): void
    {
        $this->accessToken = $values['access_token'];
        $this->tokenType = $values['token_type'];
        $this->scope = $values['scope'];
        $this->apiUrl = $values['api_url'];
        $this->expiresAt = now()->addSeconds((int) $values['expires_in']);

        Cache::put(self::CACHE_KEY, [
            'access_token' => $this->accessToken,
            'token_type' => $this->tokenType,
            'expires_at' => $this->expiresAt->toDateTimeString(),
            'scope' => $this->scope,
            'api_url' => $this->apiUrl,
        ], $this->expiresAt);
    }
}
