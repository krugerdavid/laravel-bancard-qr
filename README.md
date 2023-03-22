# Bancard QR for Laravel
[![Latest Stable Version](http://poser.pugx.org/krugerdavid/laravel-bancard-qr/v)](https://packagist.org/packages/krugerdavid/laravel-bancard-qr)
[![Daily Downloads](http://poser.pugx.org/krugerdavid/laravel-bancard-qr/d/daily)](https://packagist.org/packages/krugerdavid/laravel-bancard-qr)
[![Monthly Downloads](http://poser.pugx.org/krugerdavid/laravel-bancard-qr/d/monthly)](https://packagist.org/packages/krugerdavid/laravel-bancard-qr)
[![Total Downloads](http://poser.pugx.org/krugerdavid/laravel-bancard-qr/downloads)](https://packagist.org/packages/krugerdavid/laravel-bancard-qr)
[![License](http://poser.pugx.org/krugerdavid/laravel-bancard-qr/license)](https://packagist.org/packages/krugerdavid/laravel-bancard-qr)
[![PHP Version Require](http://poser.pugx.org/krugerdavid/laravel-bancard-qr/require/php)](https://packagist.org/packages/krugerdavid/laravel-bancard-qr)

Laravel wrapper package for Bancard QR API. More information about Bancard QR [here](https://www.bancard.com.py/pagos-qr)

## Requirements

* PHP 8 or later
* Laravel 9, 10 or later

## Instalation

Fire up Composer and require this package in your project.

    composer require krugerdavid/laravel-bancard-qr

That's it.

## Publish the config

Run the following command to publish config file,

```shell
php artisan vendor:publish --provider="KrugerDavid\LaravelBancardQR\BancardQRServiceProvider"
```

## Add ENV keys

Add the following keys on your .env file

    BANCARDQR_PUBLIC_KEY=
    BANCARDQR_PRIVATE_KEY=
    BANCARDQR_SERVICE_URL=
    BANCARDQR_COMMERCE_CODE=
    BANCARDQR_COMMERCE_BRANCH=

## How to use

Generate a QR

```php
use KrugerDavid\LaravelBancardQR\BancardQR;


$response = BancardQR::generate_qr(int $amount, string $description, ?array $promotions);
$qr_express = $response->qr_express;

```

Revert a QR code after a payment was made

```php
use KrugerDavid\LaravelBancardQR\BancardQR;

// $hook_alias is the QR alias
BancardQR::revert($hook_alias);

```

### License
The MIT License (MIT). Please see [License](LICENSE.md) File for more information  
