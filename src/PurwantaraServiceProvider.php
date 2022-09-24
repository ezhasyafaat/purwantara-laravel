<?php

namespace Ezhasyafaat\PurwantaraPayment;

use Illuminate\Support\ServiceProvider;

class PurwantaraServiceProvider extends ServiceProvider
{
    public const CONFIG_PATH = __DIR__ . '/../config/purwantara.php';

    /**
     * Bootstrap services.
     * 
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            self::CONFIG_PATH   => config_path('purwantara.php'),
        ], 'config');
    }
}