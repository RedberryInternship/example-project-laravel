<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Library\GeorgianCard;

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
        $this -> app -> bind( 'redberry.georgian-card.handler', GeorgianCard :: class );
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
