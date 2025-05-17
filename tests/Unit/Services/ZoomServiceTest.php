<?php

use Aboutnima\LaravelZoom\Exceptions\ZoomException;
use Aboutnima\LaravelZoom\Facades\Zoom;
use Aboutnima\LaravelZoom\Services\Zoom\ZoomMeetingService;
use Aboutnima\LaravelZoom\Services\Zoom\ZoomRoomService;
use Aboutnima\LaravelZoom\Services\Zoom\ZoomUserService;
use Aboutnima\LaravelZoom\Services\ZoomService;

beforeEach(function (): void {
    // Create `ZoomService` instance and request access-token
    $this->zoom = Zoom::default();
});

it('`Zoom` facade is correctly bound and returns an instance of ZoomService', function (): void {
    expect($this->zoom)->toBeInstanceOf(ZoomService::class);
});

it('`createRequest` method exists', function (): void {
    expect(method_exists($this->zoom, 'createRequest'))->toBeTrue();
});

it('can call `users/me` endpoint via `sendRequest` method and receive 200 OK status', function (): void {
    $endpoint = 'users/me';

    $response = $this->zoom->sendRequest('get', $endpoint);

    $fakeRequest = Http::fake()->get($this->zoom->tokenManager()->getApiUrl().$endpoint);

    // Just ensure the request didn't throw and returned an array
    expect($response->json())->toBeArray()
        ->not->toBeEmpty()
        ->and($response->status())->toBe($fakeRequest->status());
});

it('throws `RuntimeException` when Zoom request fails', function (): void {
    expect(
        fn () => $this->zoom->sendRequest('get', '404')
    )->toThrow(ZoomException::class);
});

//it('throws a `RuntimeException` when a Zoom request to a valid endpoint fails due to an invalid `access_token`', function (): void {
//    /**
//     * When the `clear` method is called on the `zoomTokenManager`, the `apiUrl` is reset to an empty string.
//     * To avoid issues, the full endpoint must be passed to the `sendRequest` method,
//     * and the current `apiUrl` should be stored before calling `clear()`.
//     */
//    $apiUrl = $this->zoom->tokenManager()->getApiUrl();
//    $this->zoom->tokenManager()->clear();
//
//    expect(
//        fn () => $this->zoom->sendRequest('get', $apiUrl.'/v2/users/me')
//    )->toThrow(ZoomException::class);
//});

it('`userService` method is an instance of `ZoomUserService`', function (): void {
    expect($this->zoom->userService())->toBeInstanceOf(ZoomUserService::class);
});

it('`roomService` method is an instance of `ZoomRoomService`', function (): void {
    expect($this->zoom->roomService())->toBeInstanceOf(ZoomRoomService::class);
});

it('`meetingService` method is an instance of `ZoomMeetingService`', function (): void {
    expect($this->zoom->meetingService())->toBeInstanceOf(ZoomMeetingService::class);
});
