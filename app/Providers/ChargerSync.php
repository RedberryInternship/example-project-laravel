<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Library\Chargers\Sync\Charger;
use App\Library\Chargers\Sync\Mocker;

class ChargerSync extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this -> app -> bind( 'chargerSyncer', Charger :: class );
        $this -> app -> bind( 'mockSyncer'   , Mocker  :: class );
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
