<?php

namespace App\Providers;

use App\Library\Entities\Helper;
use App\Library\Interactors\SMS;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use App\Library\Interactors\GeorgianCard;

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
        if( ! Helper :: isDev() ) 
        { 
            URL::forceScheme('https'); 
        }
    }
}
