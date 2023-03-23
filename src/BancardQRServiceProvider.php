<?php

namespace KrugerDavid\LaravelBancardQR;

use Illuminate\Support\ServiceProvider;

class BancardQRServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/bancardqr.php' => config_path('bancardqr.php')
        ]);
    }

    public function register()
    {
        $this->app->singleton(BancardQR::class, function() {
            return new BancardQR();
        });
    }
}