<?php

declare(strict_types=1);

namespace LeviLabs\LaravelOtpiq\Tests;

use LeviLabs\LaravelOtpiq\OtpiqServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            OtpiqServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('otpiq.projects.default', [
            'api_key' => 'sk_test_key',
            'base_url' => 'https://api.otpiq.com/api',
        ]);

        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        $app['config']->set('cache.default', 'array');
    }
}
