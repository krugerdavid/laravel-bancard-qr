# Bancard QR for Laravel
[![Latest Stable Version](http://poser.pugx.org/krugerdavid/laravel-bancard-qr/v)](https://packagist.org/packages/krugerdavid/laravel-bancard-qr)
[![Daily Downloads](http://poser.pugx.org/krugerdavid/laravel-bancard-qr/d/daily)](https://packagist.org/packages/krugerdavid/laravel-bancard-qr)
[![Monthly Downloads](http://poser.pugx.org/krugerdavid/laravel-bancard-qr/d/monthly)](https://packagist.org/packages/krugerdavid/laravel-bancard-qr)
[![Total Downloads](http://poser.pugx.org/krugerdavid/laravel-bancard-qr/downloads)](https://packagist.org/packages/krugerdavid/laravel-bancard-qr)
[![License](http://poser.pugx.org/krugerdavid/laravel-bancard-qr/license)](https://packagist.org/packages/krugerdavid/laravel-bancard-qr)
[![PHP Version Require](http://poser.pugx.org/krugerdavid/laravel-bancard-qr/require/php)](https://packagist.org/packages/krugerdavid/laravel-bancard-qr)

Laravel wrapper package for Bancard QR API. More information about Bancard QR [here](https://www.bancard.com.py/pagos-qr)

## What's new in v2.0

- **Manager + Facade**: Inyectable `BancardQRManager` y Facade para mejor testabilidad
- **Dependency injection**: Soporte nativo para inyección de dependencias
- **Config mejorada**: `mergeConfigFrom` — la config funciona sin publicar; timeout configurable
- **Manejo de errores**: Excepciones en lugar de retornar JSON; logging con `Log::error`
- **Tests**: Suite completa con Pest (Unit + Feature)
- **CI**: GitHub Actions para PHP 8.2–8.4 y Laravel 11–12
- **Bugs corregidos**: `revert()` usaba keys de config incorrectos; `formatException` fallaba sin response

## Requirements

* PHP 8.1 or later
* Laravel 10, 11 or later

## Installation

Fire up Composer and require this package in your project.
```bash
composer require krugerdavid/laravel-bancard-qr
```
That's it.

## Publish the config

Run the following command to publish config file,

```bash
php artisan vendor:publish --tag=bancardqr-config
```

## Add ENV keys

Add the following keys on your .env file
```
BANCARDQR_PUBLIC_KEY=
BANCARDQR_PRIVATE_KEY=
BANCARDQR_STAGING=
BANCARDQR_COMMERCE_CODE=
BANCARDQR_COMMERCE_BRANCH=
BANCARDQR_TIMEOUT=30
```

## How to use

### Using the Facade (recommended)

```php
use KrugerDavid\LaravelBancardQR\Facades\BancardQR;

$response = BancardQR::generateQr(50000, 'Payment description');
$response = BancardQR::revert($hookAlias);
```

### Using dependency injection

```php
use KrugerDavid\LaravelBancardQR\BancardQRManager;

class PaymentController
{
    public function __construct(
        private BancardQRManager $bancardQR
    ) {}

    public function create()
    {
        $response = $this->bancardQR->generateQr(50000, 'Payment');
    }
}
```

### API estática legacy (retrocompatible)

```php
use KrugerDavid\LaravelBancardQR\BancardQR;

$response = BancardQR::generate_qr(50000, 'Descripción', $promotions);
BancardQR::revert($hook_alias);
```

> **Nota**: Se recomienda migrar al Facade o inyección de dependencias.

---

## Respuestas de la API

### Generate QR — estructura de respuesta

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

### Revert — estructura de respuesta

| Parameter | Type | Description |
| --- | --- | --- |
| `status` | String | Indicates if the qr could be reversed or not. |
| `reverse` | QR object | Element with info of the QR reverted |
| `messages` | Array | In case of `status` error, list of errors |

## Credits

- [David Krüger](https://github.com/krugerdavid)

## License

The MIT License (MIT). Please see [License](LICENSE.md) File for more information  
