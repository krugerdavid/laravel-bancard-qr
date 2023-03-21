<?php

namespace KrugerDavid\LaravelBancardQR;

use Illuminate\Support\ServiceProvider;

class BancardQRServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/bancard-qr.php' => config_path('bancard-qr.php')
        ]);
    }

    public function register()
    {
        $this->app->singleton(BancardQR::class, function() {
            return new BancardQR();
        });
    }
}