<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Library\GeorgianCardHandler;

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
        $this -> app -> bind( 'redberry.georgian-card.handler', GeorgianCardHandler :: class );
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
