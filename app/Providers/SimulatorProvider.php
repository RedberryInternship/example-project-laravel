<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Library\Adapters\RealChargers\Simulator;
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
            
            $mishasBackIp       = config( 'espace.mishas_back_ip'       );
            $mishasBackProtocol = config( 'espace.mishas_back_protocol' );
            $mishasBackPort     = config( 'espace.mishas_back_port'     );
            $guzzle             = new Client();
            
            return new Simulator(
                $mishasBackProtocol,
                $mishasBackIp,
                $mishasBackPort,
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
