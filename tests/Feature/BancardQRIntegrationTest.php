<?php

use KrugerDavid\LaravelBancardQR\BancardQRManager;
use KrugerDavid\LaravelBancardQR\Facades\BancardQR;

it('resolves BancardQRManager from container', function () {
    $manager = app(BancardQRManager::class);

    expect($manager)->toBeInstanceOf(BancardQRManager::class);
});

it('resolves BancardQR facade to manager', function () {
    expect(BancardQR::getFacadeRoot())->toBeInstanceOf(BancardQRManager::class);
});

it('has correct config merged', function () {
    expect(config('bancardqr.commerce_code'))->toBe('TEST_COMMERCE');
    expect(config('bancardqr.commerce_branch'))->toBe('TEST_BRANCH');
});
