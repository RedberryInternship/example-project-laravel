<?php

namespace App\Providers;

use App\Library\Entities\Helper;
use App\Library\Interactors\SMS;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use App\Library\Interactors\GeorgianCard;
use Spatie\NovaTranslatable\Translatable;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Translatable :: defaultLocales([ 'en', 'ru','ka' ]);
        $this -> app -> bind( 'redberry.georgian-card.handler', GeorgianCard :: class );
        $this -> app -> bind( 'SMSProvider', SMS :: class );

        /**
         * Blade directive for determining which language is selected.
         */
        Blade::if('selectedlang', function($lang) {
            $userId = auth()->id();
            $savedLang = !is_null($userId)
                ? cache()->get("selectedlang.{$userId}") ?? 'ka'
                : 'ka';
          return $savedLang === $lang;
        });
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
