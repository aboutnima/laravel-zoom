<?php

use Aboutnima\LaravelZoom\Facades\Zoom;
use Aboutnima\LaravelZoom\Services\ZoomService;
use Aboutnima\LaravelZoom\Services\Zoom\ZoomRoomService;
use Aboutnima\LaravelZoom\Services\Zoom\ZoomUserService;
use Aboutnima\LaravelZoom\Exceptions\ZoomRequestException;
use Illuminate\Support\Carbon;

beforeEach(function (): void {
    // Create `ZoomService` instance and request access-token
    $this->zoom = Zoom::default();
});

it('`Zoom` facade is correctly bound and returns an instance of ZoomService', function (): void {
    expect($this->zoom)->toBeInstanceOf(ZoomService::class);
});

it('returns true when authenticated', function (): void {
    expect($this->zoom->isAuthenticated())
        ->toBeBool()
        ->toBeTrue();
});

it('returns non-empty account ID from `getAccountId`', function (): void {
    expect($this->zoom->getAccountId())
        ->toBeString()
        ->not->toBeEmpty();
});

it('returns non-empty base URL from `getBaseUrl`', function (): void {
    expect($this->zoom->getBaseUrl())
        ->toBeString()
        ->not->toBeEmpty();
});

it('returns non-empty client ID from `getClientId`', function (): void {
    expect($this->zoom->getClientId())
        ->toBeString()
        ->not->toBeEmpty();
});

it('returns non-empty client secret from `getClientSecret`', function (): void {
    expect($this->zoom->getClientSecret())
        ->toBeString()
        ->not->toBeEmpty();
});

it('returns non-empty access token from `getAccessToken`', function (): void {
    expect($this->zoom->getAccessToken())
        ->toBeString()
        ->not->toBeEmpty();
});

it('returns non-empty token type from `getTokenType`', function (): void {
    expect($this->zoom->getTokenType())
        ->toBeString()
        ->not->toBeEmpty();
});

it('returns numeric value from `getExpiresIn`', function (): void {
    expect($this->zoom->getExpiresIn())
        ->toBeFloat()
        ->toBeGreaterThan(0);
});

it('returns valid Carbon instance from `getExpiresAt`', function (): void {
    expect($this->zoom->getExpiresAt())
        ->toBeInstanceOf(Carbon::class);
});

it('returns non-empty scope string from `getScope`', function (): void {
    expect($this->zoom->getScope())
        ->toBeString()
        ->not->toBeEmpty();
});

it('returns non-empty API URL from `getApiUrl`', function (): void {
    expect($this->zoom->getApiUrl())
        ->toBeString()
        ->not->toBeEmpty();
});

it('`sendRequest` method exists', function (): void {
    expect(method_exists($this->zoom, 'sendRequest'))->toBeTrue();
});

it('can call `users/me` endpoint via `sendRequest` method and receive 200 OK status', function (): void {
    $endpoint = 'users/me';

    $response = $this->zoom->sendRequest('get', $endpoint);

    $fakeRequest = Http::fake()->get($this->zoom->getApiUrl().$endpoint);

    // Just ensure the request didn't throw and returned an array
    expect($response->json())->toBeArray()
        ->not->toBeEmpty()
        ->and($response->status())->toBe($fakeRequest->status());
});

it('throws `RuntimeException` when Zoom request fails', function (): void {
    $this->zoom->sendRequest('get', '404');
})->throws(ZoomRequestException::class);

it('`userService` method is exists', function (): void {
    expect(method_exists($this->zoom, 'userService'))->toBeTrue();
});

it('`userService` method is an instance of `ZoomUserService`', function (): void {
    expect($this->zoom->userService())->toBeInstanceOf(ZoomUserService::class);
});

it('`roomService` method is an instance of `ZoomRoomService`', function (): void {
    expect($this->zoom->roomService())->toBeInstanceOf(ZoomRoomService::class);
});
