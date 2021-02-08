<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Library\Testing\MishasCharger;
use App\Library\Testing\ChargerMocker;

class ChargerSync extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this -> app -> bind( 'chargerSyncer', MishasCharger :: class );
        $this -> app -> bind( 'mockSyncer'   , ChargerMocker :: class );
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
