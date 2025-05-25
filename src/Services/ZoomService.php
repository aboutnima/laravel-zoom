<?php

declare(strict_types=1);

namespace Aboutnima\LaravelZoom\Services;

use Aboutnima\LaravelZoom\Auth\ZoomTokenManager;
use Aboutnima\LaravelZoom\Contracts\Services\ZoomServiceInterface;
use Aboutnima\LaravelZoom\Exceptions\ZoomException;
use Closure;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

final readonly class ZoomService implements ZoomServiceInterface
{
    public function __construct(
        private ZoomTokenManager $tokenManager
    ) {}

    public function default(): self
    {
        return $this;
    }

    public function tokenManager(): ZoomTokenManager
    {
        return $this->tokenManager;
    }

    public function sendRequest(
        string $method,
        string $endpoint,
        array $query = [],
        array $payload = [],
        ?Closure $success = null,
        ?Closure $error = null,
    ): Response {
        try {
            $response = $this
                ->createRequest()
                ->withQueryParameters($query)
                ->{$method}($endpoint, $payload);

            $statusCode = $response->getStatusCode();
            $data = $response->collect();

            if ($statusCode >= 200 && $statusCode < 300) {
                $success?->call($this, $statusCode, $data);

                return $response;
            }

            $error?->call($this, $statusCode, $data->get('message', 'Unknown error'), $data);

            return $response;

        } catch (RequestException $e) {
            $error?->call($this, null, $e->getMessage(), null);
            throw ZoomException::failed($e->getMessage());
        }
    }

    private function createRequest(): PendingRequest
    {
        return Http::withHeaders([
            'Authorization' => "{$this->tokenManager->getTokenType()} {$this->tokenManager->getAccessToken()}",
            'Content-Type' => 'application/json',
        ])->baseUrl($this->tokenManager->getApiUrl().'/v2');
    }
}
