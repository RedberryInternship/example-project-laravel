<?php

namespace App\Providers;

use App\Library\Interactors\GeorgianCard;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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
        if(env('APP_ENV') !== 'local') 
        { 
            URL::forceScheme('https'); 
        }
    }
}
