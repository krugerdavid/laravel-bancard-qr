<?php

namespace KrugerDavid\LaravelBancardQR;

/**
 * Static proxy for backward compatibility.
 * Prefer using the BancardQR facade or injecting BancardQRManager.
 *
 * @deprecated Use BancardQR facade or BancardQRManager directly
 */
class BancardQR
{
    protected static function manager(): BancardQRManager
    {
        return app(BancardQRManager::class);
    }

    public static function isStaging(): bool
    {
        return (bool) config('bancardqr.staging', true);
    }

    public static function baseUrl(): string
    {
        return self::isStaging()
            ? rtrim(BancardQRManager::STAGING_URL, '/') . '/'
            : rtrim(BancardQRManager::PRODUCTION_URL, '/') . '/';
    }

    /**
     * Generate a QR code for a payment.
     *
     * @param  int  $amount  Amount in guaraníes
     * @param  string  $description  Description of the sale
     * @param  array|null  $promotions  Optional promotions array
     * @return object
     */
    public static function generate_qr(int $amount, string $description, ?array $promotions = null): object
    {
        return self::manager()->generateQr($amount, $description, $promotions);
    }

    /**
     * Revert a QR payment by hook alias.
     *
     * @param  string  $hook_alias  The QR alias from the payment
     * @return object
     */
    public static function revert(string $hook_alias): object
    {
        return self::manager()->revert($hook_alias);
    }
}
