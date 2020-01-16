<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        \Spatie\NovaTranslatable\Translatable::defaultLocales(['en', 'ru','ka']);

        $this->app->make('Redberry\GeorgianCardGateway\PaymentController');
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
