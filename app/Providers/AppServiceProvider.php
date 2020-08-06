<?php

namespace App\Providers;

use App\Library\Interactors\SMS;
use App\Library\Interactors\GeorgianCard;
use Illuminate\Support\Facades\URL;
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

        $this -> app -> bind( 'redberry.georgian-card.handler', GeorgianCard :: class );

        $this -> app -> bind( 'SMSProvider', SMS :: class );
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
