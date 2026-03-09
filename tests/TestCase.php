<?php

namespace KrugerDavid\LaravelBancardQR\Tests;

use KrugerDavid\LaravelBancardQR\BancardQRServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            BancardQRServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        config()->set('bancardqr.private_key', 'test_private_key');
        config()->set('bancardqr.public_key', 'test_public_key');
        config()->set('bancardqr.commerce_code', 'TEST_COMMERCE');
        config()->set('bancardqr.commerce_branch', 'TEST_BRANCH');
        config()->set('bancardqr.staging', true);
    }
}
