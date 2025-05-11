<?php

declare(strict_types=1);

namespace Tests;

use Dotenv\Dotenv;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        $this->loadEnvironmentVariables();

        parent::setUp();
    }

    protected function getPackageProviders($app): array
    {
        return [
            \Aboutnima\LaravelZoom\LaravelZoomServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app): array
    {
        return [
            'Zoom' => \Aboutnima\LaravelZoom\Facades\Zoom::class,
        ];
    }

    private function loadEnvironmentVariables(): void
    {
        // Load .env.testing from package root
        if (file_exists(dirname(__DIR__) . '/.env.testing')) {
            $dotenv = Dotenv::createImmutable(dirname(__DIR__), '.env.testing');
            $dotenv->load();
        }
    }
}
