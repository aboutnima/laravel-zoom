<?php

declare(strict_types=1);

namespace Aboutnima\LaravelZoom\Services;

use Aboutnima\LaravelZoom\Auth\ZoomTokenManager;
use Aboutnima\LaravelZoom\Contracts\Services\ZoomServiceInterface;
use Aboutnima\LaravelZoom\Exceptions\ZoomException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Closure;

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

            if ($statusCode >= 200 && $statusCode < 300) {
                if (! is_null($success)) {
                    $success($statusCode, $response);
                }
            } elseif (! is_null($error)) {
                $error($statusCode, $response->collect()->get('message', 'Unknown error'), $response);
            }

            return $response;
        } catch (RequestException $e) {
            if (! is_null($error)) {
                $error(null, $e->getMessage(), null);
            }
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
