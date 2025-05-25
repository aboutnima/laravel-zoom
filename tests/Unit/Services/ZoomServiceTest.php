<?php

use Aboutnima\LaravelZoom\Exceptions\ZoomException;
use Aboutnima\LaravelZoom\Facades\Zoom;
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

//it('throws `RuntimeException` when Zoom request fails', function (): void {
//    expect(
//        fn () => $this->zoom->sendRequest('get', '!@#')
//    )->toThrow(ZoomException::class);
//});
