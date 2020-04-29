<?php

namespace App\Traits\Testing;

use App\Facades\Simulator;

use Illuminate\Support\Facades\DB;

use App\ConnectorType;
use App\ChargerConnectorType;

use App\Charger as AppCharger;


trait Charger
{
  private $initiated;

  public function create_order_with_charger_id_of_29()
  {
    $this -> initiated = true;

    Simulator :: upAndRunning( 29 );
    Simulator :: plugOffCable( 29 );
    sleep( 3 );

    $chargerConnectorType = factory( ChargerConnectorType :: class ) 
      -> create(
        [
          'charger_id' => factory( AppCharger :: class ) -> create([ 'charger_id' => 29 ]),
        ]
      );

    $this -> withHeader( 'Authorization', 'Bearer ' . $this -> token )
          -> post($this -> uri .'charging/start', [
              'charger_connector_type_id' => $chargerConnectorType -> id,
              'charging_type'             => 'FULL-CHARGE'
              ]);
  }

  public function tear_down_order_data_with_charger_id_of_29()
  {
    $this -> initiated && Simulator :: plugOffCable( 29 );

    ChargerConnectorType :: truncate();
    ConnectorType        :: truncate();
    DB                   :: table('chargers') -> delete();
  }
}