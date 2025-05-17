<?php

use Aboutnima\LaravelZoom\Auth\ZoomTokenManager;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

beforeEach(function (): void {
    // Create `ZoomTokenManager` instance
    $this->zoomTokenManager = app(ZoomTokenManager::class);
});

it('`requestAccessToken` method exists', function (): void {
    expect(method_exists($this->zoomTokenManager, 'requestAccessToken'))->toBeTrue();
});

it('`setAccessToken` method exists', function (): void {
    expect(method_exists($this->zoomTokenManager, 'setAccessToken'))->toBeTrue();
});

it('`isAuthenticated` method return false when `accessToken` is not cached', function (): void {
    $this->zoomTokenManager->clear();

    expect($this->zoomTokenManager->isAuthenticated())->toBeFalse();
});

it('`isAuthenticated` method return false when `expires_at` is past', function (): void {
    Cache::shouldReceive('get')
        ->with($this->zoomTokenManager->getCacheKey())
        ->once()
        ->andReturn([
            'expires_at' => Carbon::now()->subMinute(),
            'access_token' => $this->zoomTokenManager->getAccessToken(),
        ]);

    Cache::shouldReceive('forget')
        ->with($this->zoomTokenManager->getCacheKey())
        ->once();

    expect($this->zoomTokenManager->isAuthenticated())->toBeFalse();
});

it('`isAuthenticated` method return false when `access_token` is not valid', function (): void {
    Cache::shouldReceive('get')
        ->with($this->zoomTokenManager->getCacheKey())
        ->once()
        ->andReturn([
            'expires_at' => Carbon::now()->addMinute(),
            'access_token' => 'invalid-access-token',
        ]);

    Cache::shouldReceive('forget')
        ->with($this->zoomTokenManager->getCacheKey())
        ->once();

    expect($this->zoomTokenManager->isAuthenticated())->toBeFalse();
});

it('request failed and exception throw on invalid zoom credentials', function (): void {
    $this->zoomTokenManager->clear();

    expect(
        fn (): \Aboutnima\LaravelZoom\Auth\ZoomTokenManager => new ZoomTokenManager(
            'https://zoom.us/oauth/token',
            'invalid-account-id',
            'invalid-client-id',
            'invalid-client-secret',
        )
    )->toThrow(\RuntimeException::class);
});

it('returns true when authenticated', function (): void {
    expect($this->zoomTokenManager->isAuthenticated())
        ->toBeBool()
        ->toBeTrue();
});

it('returns non-empty account ID from `getAccountId`', function (): void {
    expect($this->zoomTokenManager->getAccountId())
        ->toBeString()
        ->not->toBeEmpty();
});

it('returns non-empty base URL from `getBaseUrl`', function (): void {
    expect($this->zoomTokenManager->getBaseUrl())
        ->toBeString()
        ->not->toBeEmpty();
});

it('returns non-empty client ID from `getClientId`', function (): void {
    expect($this->zoomTokenManager->getClientId())
        ->toBeString()
        ->not->toBeEmpty();
});

it('returns non-empty client secret from `getClientSecret`', function (): void {
    expect($this->zoomTokenManager->getClientSecret())
        ->toBeString()
        ->not->toBeEmpty();
});

it('returns non-empty access token from `getAccessToken`', function (): void {
    expect($this->zoomTokenManager->getAccessToken())
        ->toBeString()
        ->not->toBeEmpty();
});

it('returns non-empty token type from `getTokenType`', function (): void {
    expect($this->zoomTokenManager->getTokenType())
        ->toBeString()
        ->not->toBeEmpty();
});

it('returns numeric value from `getExpiresIn`', function (): void {
    expect($this->zoomTokenManager->getExpiresIn())
        ->toBeFloat()
        ->toBeGreaterThan(0);
});

it('returns valid Carbon instance from `getExpiresAt`', function (): void {
    expect($this->zoomTokenManager->getExpiresAt())
        ->toBeInstanceOf(Carbon::class);
});

it('returns non-empty scope string from `getScope`', function (): void {
    expect($this->zoomTokenManager->getScope())
        ->toBeString()
        ->not->toBeEmpty();
});

it('returns non-empty API URL from `getApiUrl`', function (): void {
    expect($this->zoomTokenManager->getApiUrl())
        ->toBeString()
        ->not->toBeEmpty();
});

it('`clear` method exists and can clear the cache', function (): void {
    $this->zoomTokenManager->clear();

    expect($this->zoomTokenManager->isAuthenticated())
        ->toBeFalse()
        ->and(Cache::get($this->zoomTokenManager->getCacheKey()))->toBeNull();
});
