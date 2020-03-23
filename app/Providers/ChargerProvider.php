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
           
            $mishasBackIp = config('espace.mishas_back_ip');
            $mishasBackProtocol = config('espace.mishas_back_protocol');     
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
