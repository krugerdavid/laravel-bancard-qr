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
```bash
composer require krugerdavid/laravel-bancard-qr
```
That's it.

## Publish the config

Run the following command to publish config file,

```bash
php artisan vendor:publish --provider="KrugerDavid\LaravelBancardQR\BancardQRServiceProvider"
```

## Add ENV keys

Add the following keys on your .env file
```
BANCARDQR_PUBLIC_KEY=
BANCARDQR_PRIVATE_KEY=
BANCARDQR_STAGING=
BANCARDQR_COMMERCE_CODE=
BANCARDQR_COMMERCE_BRANCH=
```
## How to use
### Generate a QR
Generate a QR code for a payment.
```php
use KrugerDavid\LaravelBancardQR\BancardQR;

$response = BancardQR::generate_qr(int $amount, string $description, ?array $promotions);
```

The response object will have the following structure

| Parameter | Type | Description |
| --- | --- | --- |
| `status` | String | Indicates if the qr could be generated or not. |
| `qr_express` | QR object | Element with qr express data. |
| `supported_clients` | Array | List of clients that support payment with QR. |

*QR Object*

| Parameter | Type | Description |
| --- | --- | --- |
| `amount` | Number | Amount in guaraníes. |
| `hook_alias` | String | Alias of the payment (from the QR) |
| `description` | String | Description of the sale entered by the merchant (Optional, the merchant may not enter a description) |
| `url` | String | URL where the generated QR image is located (in PNG format). This is the image that the store must display in its system. |
| `created_at` | String | Date time of creation of the QR in format dd/mm/yyyy HH:mm:ss |
| `qr_data` | String | QR data in EMVCo format. |

*Supported Clients List*

| Parameter | Type | Description |
| --- | --- | --- |
| `name` | String | Client name. |
| `logo_url` | String | Client logo url |

### Revert QR Payment
Revert a QR code after a payment was made
```php
use KrugerDavid\LaravelBancardQR\BancardQR;

// $hook_alias is the QR alias
BancardQR::revert($hook_alias);
```
## Credits

- [David Krüger](https://github.com/krugerdavid)

## License

The MIT License (MIT). Please see [License](LICENSE.md) File for more information  
