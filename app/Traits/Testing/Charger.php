<?php

namespace App\Traits\Testing;

use App\Enums\ConnectorType as ConnectorTypeEnum;
use App\Enums\ChargingType as ChargingTypeEnum;
use App\Facades\Charger as MishasCharger;
use Illuminate\Support\Facades\DB;
use App\Charger as AppCharger;
use App\ChargerConnectorType;
use App\Facades\Simulator;
use App\ConnectorType;
use App\UserCard;

trait Charger
{
  private $initiated;

  public function create_order_with_charger_id_of_29()
  {
    $this -> initiated = true;

    $this -> makeChargerFree();

    $userCard = factory( UserCard :: class ) -> create();

    $chargerConnectorType = factory( ChargerConnectorType :: class ) 
      -> create(
        [
          'charger_id'        => factory( AppCharger :: class ) -> create([ 'charger_id' => 29 ]),
          'connector_type_id' => ConnectorType :: whereName( ConnectorTypeEnum :: TYPE_2 ) -> first(),
        ]
      );

    $this -> withHeader( 'Authorization', 'Bearer ' . $this -> token )
          -> post($this -> uri .'charging/start', [
              'charger_connector_type_id' => $chargerConnectorType -> id,
              'charging_type'             => ChargingTypeEnum :: FULL_CHARGE,
              'user_card_id'              => $userCard -> id,
              ]);
  }

  public function tear_down_order_data_with_charger_id_of_29()
  {
    $this -> initiated && Simulator :: plugOffCable( 29 );

    ChargerConnectorType :: truncate();
    ConnectorType        :: truncate();
    DB                   :: table('chargers') -> delete();
  }

  public function makeChargerFree()
  {
    if( ! MishasCharger :: isChargerFree( 29 ))
    {
      Simulator :: upAndRunning( 29 );
      Simulator :: plugOffCable( 29 );
      sleep( 3 );
    }
  }
}