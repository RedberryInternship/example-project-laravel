<?php

namespace Redberry\GeorgianCardGateway;

use Illuminate\Support\ServiceProvider;

class GeorgianCardGatewayServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        include __DIR__.'/routes.php';

        $this->publishes([
            __DIR__.'/config.php' => config_path('georgian-card-gateway.php'),
        ]);
    }
}
