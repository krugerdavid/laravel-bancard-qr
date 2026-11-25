# Bancard QR for Laravel

[![Latest Version](https://img.shields.io/packagist/v/krugerdavid/laravel-bancard-qr.svg?style=flat-square)](https://packagist.org/packages/krugerdavid/laravel-bancard-qr)
[![Total Downloads](https://img.shields.io/packagist/dt/krugerdavid/laravel-bancard-qr.svg?style=flat-square)](https://packagist.org/packages/krugerdavid/laravel-bancard-qr)
[![License](https://img.shields.io/packagist/l/krugerdavid/laravel-bancard-qr.svg?style=flat-square)](https://packagist.org/packages/krugerdavid/laravel-bancard-qr)
[![PHP Version](https://img.shields.io/packagist/php-v/krugerdavid/laravel-bancard-qr.svg?style=flat-square)](https://packagist.org/packages/krugerdavid/laravel-bancard-qr)

Laravel wrapper for Bancard QR API. Más información sobre Bancard QR [aquí](https://www.bancard.com.py/pagos-qr).

## Why this package exists

Bancard QR permite cobros escaneando un código QR desde la app del banco. Este paquete encapsula la API de generación de QR y reversa en una interfaz Laravel: Facade, Manager inyectable, config publicable y manejo de errores con excepciones. Evita duplicar lógica de autenticación Basic Auth y URLs de staging/producción.

## Requirements

* PHP 8.1+
* Laravel 10, 11 o 12

## Installation

```bash
composer require krugerdavid/laravel-bancard-qr
```

## Configuration

Publicar la config:

```bash
php artisan vendor:publish --tag=bancardqr-config
```

Variables de entorno (.env):

```
BANCARDQR_PUBLIC_KEY=
BANCARDQR_PRIVATE_KEY=
BANCARDQR_STAGING=true
BANCARDQR_COMMERCE_CODE=
BANCARDQR_COMMERCE_BRANCH=
BANCARDQR_TIMEOUT=30
```

## Usage

### Usando el Facade (recomendado)

```php
use KrugerDavid\LaravelBancardQR\Facades\BancardQR;

$response = BancardQR::generateQr(50000, 'Payment description');
$response = BancardQR::revert($hookAlias);
```

### Usando inyección de dependencias

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

## Related packages

- [laravel-bancard](https://github.com/krugerdavid/laravel-bancard) — Bancard VPOS (tarjetas de crédito/débito)
- [laravel-pagopar](https://github.com/krugerdavid/laravel-pagopar) — Pagopar (transferencias, billeteras, múltiples medios)

## Used in Paraguay / production-ready

Este paquete está en uso en producción en comercios de Paraguay. Bancard QR es una opción popular para pagos presenciales o desde móvil escaneando el código.

## Credits

- [David Krüger](https://github.com/krugerdavid)

## License

MIT
