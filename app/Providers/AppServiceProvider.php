<?php

namespace App\Providers;

use App\Helpers\App;
use App\Library\Interactors\SMS;
use Illuminate\Support\Facades\URL;
use App\Library\Interactors\GeorgianCard;
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
        if( ! App :: dev() ) 
        { 
            URL::forceScheme('https'); 
        }
    }
}
