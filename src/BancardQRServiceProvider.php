<?php

namespace KrugerDavid\LaravelBancardQR;

use Illuminate\Support\ServiceProvider;

class BancardQRServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/bancardqr.php', 'bancardqr');

        $this->app->singleton(BancardQRManager::class, function ($app) {
            return new BancardQRManager($app['config']['bancardqr']);
        });

        $this->app->alias(BancardQRManager::class, 'bancardqr');
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/bancardqr.php' => config_path('bancardqr.php'),
            ], 'bancardqr-config');
        }
    }
}
