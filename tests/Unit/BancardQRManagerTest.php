<?php

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use KrugerDavid\LaravelBancardQR\BancardQRManager;
use KrugerDavid\LaravelBancardQR\Tests\TestCase;

beforeEach(function () {
    $this->config = [
        'private_key' => 'test_private',
        'public_key' => 'test_public',
        'commerce_code' => 'COMMERCE',
        'commerce_branch' => 'BRANCH',
        'staging' => true,
        'timeout' => 30,
    ];
});

it('generates QR successfully', function () {
    $mock = new MockHandler([
        new Response(200, [], json_encode([
            'status' => 'success',
            'qr_express' => [
                'amount' => 50000,
                'hook_alias' => 'alias-123',
                'url' => 'https://qr.example.com/image.png',
            ],
        ])),
    ]);

    $handlerStack = HandlerStack::create($mock);
    $client = new Client(['handler' => $handlerStack]);

    $manager = new BancardQRManager($this->config);
    $refObject = new ReflectionObject($manager);
    $refProperty = $refObject->getProperty('client');
    $refProperty->setAccessible(true);
    $refProperty->setValue($manager, $client);

    $response = $manager->generateQr(50000, 'Test payment');

    expect($response->status)->toBe('success');
    expect($response->qr_express->amount)->toBe(50000);
    expect($response->qr_express->hook_alias)->toBe('alias-123');
});

it('reverts payment successfully', function () {
    $mock = new MockHandler([
        new Response(200, [], json_encode([
            'status' => 'success',
            'reverse' => ['hook_alias' => 'alias-123'],
        ])),
    ]);

    $handlerStack = HandlerStack::create($mock);
    $client = new Client(['handler' => $handlerStack]);

    $manager = new BancardQRManager($this->config);
    $refObject = new ReflectionObject($manager);
    $refProperty = $refObject->getProperty('client');
    $refProperty->setAccessible(true);
    $refProperty->setValue($manager, $client);

    $response = $manager->revert('alias-123');

    expect($response->status)->toBe('success');
});

it('throws on empty hook alias for revert', function () {
    $manager = new BancardQRManager($this->config);

    expect(fn () => $manager->revert(''))->toThrow(InvalidArgumentException::class, 'Hook alias cannot be empty');
    expect(fn () => $manager->revert('   '))->toThrow(InvalidArgumentException::class, 'Hook alias cannot be empty');
});

it('throws on API error for generateQr', function () {
    $mock = new MockHandler([
        new Response(400, [], json_encode([
            'status' => 'error',
            'messages' => [['key' => 'Invalid amount']],
        ])),
    ]);

    $handlerStack = HandlerStack::create($mock);
    $client = new Client(['handler' => $handlerStack]);

    $manager = new BancardQRManager($this->config);
    $refObject = new ReflectionObject($manager);
    $refProperty = $refObject->getProperty('client');
    $refProperty->setAccessible(true);
    $refProperty->setValue($manager, $client);

    expect(fn () => $manager->generateQr(0, 'Test'))->toThrow(Exception::class, 'Bancard QR API error');
});
