<?php

use Aboutnima\LaravelZoom\Facades\Zoom;
use Aboutnima\LaravelZoom\Services\Zoom\ZoomRoomService;
use Aboutnima\LaravelZoom\Services\Zoom\ZoomUserService;
use Aboutnima\LaravelZoom\Services\ZoomService;
use Illuminate\Support\Carbon;

beforeEach(function () {
    // Create `ZoomService` instance and request access-token
    $this->zoom = Zoom::default();
});

it('`Zoom` facade is correctly bound and returns an instance of ZoomService', function () {
    expect($this->zoom)->toBeInstanceOf(ZoomService::class);
});

it('returns true when authenticated', function () {
    expect($this->zoom->isAuthenticated())
        ->toBeBool()
        ->toBeTrue();
});

it('returns non-empty account ID from `getAccountId`', function () {
    expect($this->zoom->getAccountId())
        ->toBeString()
        ->not->toBeEmpty();
});

it('returns non-empty base URL from `getBaseUrl`', function () {
    expect($this->zoom->getBaseUrl())
        ->toBeString()
        ->not->toBeEmpty();
});

it('returns non-empty client ID from `getClientId`', function () {
    expect($this->zoom->getClientId())
        ->toBeString()
        ->not->toBeEmpty();
});

it('returns non-empty client secret from `getClientSecret`', function () {
    expect($this->zoom->getClientSecret())
        ->toBeString()
        ->not->toBeEmpty();
});

it('returns non-empty access token from `getAccessToken`', function () {
    expect($this->zoom->getAccessToken())
        ->toBeString()
        ->not->toBeEmpty();
});

it('returns non-empty token type from `getTokenType`', function () {
    expect($this->zoom->getTokenType())
        ->toBeString()
        ->not->toBeEmpty();
});

it('returns numeric value from `getExpiresIn`', function () {
    expect($this->zoom->getExpiresIn())
        ->toBeFloat()
        ->toBeGreaterThan(0);
});

it('returns valid Carbon instance from `getExpiresAt`', function () {
    expect($this->zoom->getExpiresAt())
        ->toBeInstanceOf(Carbon::class);
});

it('returns non-empty scope string from `getScope`', function () {
    expect($this->zoom->getScope())
        ->toBeString()
        ->not->toBeEmpty();
});

it('returns non-empty API URL from `getApiUrl`', function () {
    expect($this->zoom->getApiUrl())
        ->toBeString()
        ->not->toBeEmpty();
});

it('`sendRequest` method exists', function () {
    expect(method_exists($this->zoom, 'sendRequest'))->toBeTrue();
});

it('can call `users/me` endpoint via `sendRequest` method and receive 200 OK status', function () {
    $endpoint = 'users/me';

    $response = $this->zoom->sendRequest('get', $endpoint);

    $fakeRequest = Http::fake()->get($this->zoom->getApiUrl().$endpoint);

    // Just ensure the request didn't throw and returned an array
    expect($response->json())->toBeArray()
        ->not->toBeEmpty()
        ->and($response->status())->toBe($fakeRequest->status());
});

it('`userService` method is exists', function () {
    expect(method_exists($this->zoom, 'userService'))->toBeTrue();
});

it('`userService` method is an instance of `ZoomUserService`', function () {
    expect($this->zoom->userService())->toBeInstanceOf(ZoomUserService::class);
});

it('`roomService` method is an instance of `ZoomRoomService`', function () {
    expect($this->zoom->roomService())->toBeInstanceOf(ZoomRoomService::class);
});
