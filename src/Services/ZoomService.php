<?php

declare(strict_types=1);

namespace Aboutnima\LaravelZoom\Services;

use Aboutnima\LaravelZoom\Auth\ZoomZoomTokenManager;
use Aboutnima\LaravelZoom\Contracts\Services\ZoomServiceInterface;
use Aboutnima\LaravelZoom\Exceptions\ZoomException;
use Aboutnima\LaravelZoom\Services\Zoom\ZoomRoomService;
use Aboutnima\LaravelZoom\Services\Zoom\ZoomUserService;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

final readonly class ZoomService implements ZoomServiceInterface
{
    public function __construct(
        private ZoomZoomTokenManager $tokenManager
    ) {}

    private function createRequest(): PendingRequest
    {
        return Http::withHeaders([
            'Authorization' => "{$this->tokenManager->getTokenType()} {$this->tokenManager->getAccessToken()}",
            'Content-Type' => 'application/json',
        ])->baseUrl($this->tokenManager->getApiUrl().'/v2');
    }

    public function sendRequest(
        string $method,
        string $endpoint,
        array $query = [],
        array $payload = []
    ): Response {
        try {
            $response = $this
                ->createRequest()
                ->withQueryParameters($query)
                ->{$method}($endpoint, $payload);

            $data = $response->collect();
            $code = $data->get('code', $response->getStatusCode());

            if ($code < 200 || $code >= 300) {
                throw ZoomException::failed($data->get('message', 'Unknown error'));
            }

            return $response;
        } catch (RequestException $e) {
            throw ZoomException::failed($e->getMessage());
        }
    }

    public function userService(): ZoomUserService
    {
        return app(ZoomUserService::class);
    }

    public function roomService(): ZoomRoomService
    {
        return app(ZoomRoomService::class);
    }
}
