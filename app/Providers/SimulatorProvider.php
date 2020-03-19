<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Library\Chargers\Simulator;
use \GuzzleHttp\Client;


class SimulatorProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {   

        $this -> app -> bind('simulator', function(){
            
            $mishasBackIp = env('MISHAS_BACK_IP') ?? '13.92.63.164';
            $mishasBackProtocol = env('MISHAS_BACK_PROTOCOL') ?? 'http';    
            $guzzle = new Client();
            
            return new Simulator(
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
        //
    }
}
