<?php

namespace KrugerDavid\LaravelBancardQR\Facades;

use Illuminate\Support\Facades\Facade;
use KrugerDavid\LaravelBancardQR\BancardQRManager;

/**
 * @method static object generateQr(int $amount, string $description, ?array $promotions = null)
 * @method static object revert(string $hookAlias)
 *
 * @see \KrugerDavid\LaravelBancardQR\BancardQRManager
 */
class BancardQR extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return BancardQRManager::class;
    }
}
