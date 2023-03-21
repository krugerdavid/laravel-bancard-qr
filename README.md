# Bancard QR for Laravel

Laravel wrapper package for Bancard QR API.

## Instalation

Install the package by the following command

    composer require krugerdavid/laravel-bancard-qr

## Publish the config

Run the following command to publish config file,

    php artisan vendor:publish

And select the proper provider

    KrugerDavid\LaravelBancardQR\BancardQRServiceProvider

## Add ENV keys

Add the following keys on your .env file

    BANCARDQR_PUBLIC_KEY=
    BANCARDQR_PRIVATE_KEY=
    BANCARDQR_SERVICE_URL=
    BANCARDQR_COMMERCE_CODE=
    BANCARDQR_COMMERCE_BRANCH=

## How to use

Import the library

    use KrugerDavid\LaravelBancardQR\BancardQR;

For generating a QR call

    $response = BancardQR::generate_qr(int $amount, string $description, ?array $promotions');
    $response = $response->getData();

The response will contain the qr_express object and the supported clients as well.

### License
The MIT License (MIT). Please see [License](LICENSE.md) File for more information  
