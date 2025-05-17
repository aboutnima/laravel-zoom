<?php

declare(strict_types=1);

namespace Aboutnima\LaravelZoom\Auth;

use Aboutnima\LaravelZoom\Contracts\Auth\ZoomTokenManagerInterface;
use Aboutnima\LaravelZoom\Exceptions\ZoomException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

final class ZoomZoomTokenManager implements ZoomTokenManagerInterface
{
    private const string CACHE_KEY = 'access_token';

    private string $accessToken = '';

    private string $tokenType = '';

    private string $scope = '';

    private string $apiUrl = '';

    private Carbon $expiresAt;

    public function __construct(
        private readonly string $baseUrl,
        private readonly string $accountId,
        private readonly string $clientId,
        private readonly string $clientSecret,
    ) {
        $this->requestAccessToken();
    }

    public function default(): self
    {
        return $this;
    }

    public function clear(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    public function isAuthenticated(): bool
    {
        // Helper closure to handle invalid authentication:
        $invalidateAndReturnFalse = static function (): bool {
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

    public function getExpiresAt(): Carbon
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

    private function requestAccessToken(): void
    {
        $cached = Cache::get(self::CACHE_KEY);

        /**
         * Check if a cached token exists and is still valid (not expired).
         * If valid, reuse it; otherwise, make a new request and refresh access token.
         */
        if (
            $cached &&
            isset($cached['access_token'], $cached['expires_at']) &&
            Carbon::parse($cached['expires_at'])->isFuture()
        ) {
            $this->setAccessToken($cached);

            return;
        }

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

        $this->setAccessToken($response->json(), true);
    }

    private function setAccessToken(array $values, bool $updateCache = false): void
    {
        $this->accessToken = $values['access_token'];
        $this->tokenType = $values['token_type'];
        $this->scope = $values['scope'];
        $this->apiUrl = $values['api_url'];

        if (isset($values['expires_in'])) {
            // From fresh Zoom API response
            $this->expiresAt = now()->addSeconds((int) $values['expires_in']);
        } elseif (isset($values['expires_at'])) {
            // From cache
            $this->expiresAt = Carbon::parse($values['expires_at']);
        } else {
            throw new \RuntimeException('Missing `expires_at` or `expires_in` for access token.');
        }

        if ($updateCache) {
            Cache::put(self::CACHE_KEY, [
                'access_token' => $this->accessToken,
                'token_type' => $this->tokenType,
                'expires_at' => $this->expiresAt->toDateTimeString(),
                'scope' => $this->scope,
                'api_url' => $this->apiUrl,
            ], $this->expiresAt);
        }
    }
}
