<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Library\Chargers\Charger;
use \GuzzleHttp\Client;


class ChargerProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

        

        $this -> app -> bind('charger', function() {
           
            $mishasBackIp = env('MISHAS_BACK_IP') ?? '13.92.63.164';
            $mishasBackProtocol = env('MISHAS_BACK_PROTOCOL') ?? 'http';    
            $guzzle = new Client();
                
            return new Charger(
                    $mishasBackProtocol, 
                    $mishasBackIp, 
                    $guzzle
                );

        });
        
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        
    }
}
