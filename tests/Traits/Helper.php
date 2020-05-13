<?php

namespace Tests\Traits;

use App\User;
use App\Order;
use App\Charger;
use App\UserCard;
use App\ChargingPrice;
use App\ConnectorType;
use App\FastChargingPrice;
use App\ChargerConnectorType;

use App\Facades\Simulator;
use Illuminate\Support\Facades\DB;
use App\Facades\Charger as MishasCharger;

use App\Enums\ChargingType as ChargingTypeEnum;
use App\Enums\ConnectorType as ConnectorTypeEnum;


Trait Helper
{

  private $initiated;


  public function create_user_and_return_token($phone_number = '+995591935080', $password = '+995591935080')
  {
    factory( User :: class ) -> create(
      [
        'phone_number'  => $phone_number,
        'password'      => bcrypt($password),
      ]
    );

    $response = $this -> post('/api/app/V1/login',[
      'phone_number'  => $phone_number,
      'password'      => $password,
    ]);

    $token = $response -> decodeResponseJson()[ 'access_token' ];

    return $token;
  }

  private function prepare_charger_connector_type()
  {

    $charger              = factory( Charger :: class ) -> create([ 'charger_id' => 29 ]);
    $connectorTypeId      = ConnectorType :: whereName( ConnectorTypeEnum :: TYPE_2 ) -> first();
    $chargerConnectorType = factory( ChargerConnectorType :: class ) -> create(
      [
        'charger_id'        => $charger -> id,
        'connector_type_id' => $connectorTypeId,
      ]
    );
    
    return $chargerConnectorType;
  }

  public function create_order_with_charger_id_of_29( $user_id = null )
  {
    $this -> initiated = true;

    $this -> make_charger_free();

    $userCard = factory( UserCard :: class ) -> create([ 'user_id' => $user_id ?: 7 ]);

    $chargerConnectorType = factory( ChargerConnectorType :: class ) 
      -> create(
        [
          'charger_id'        => factory( Charger :: class ) -> create([ 'charger_id' => 29 ]),
          'connector_type_id' => ConnectorType :: whereName( ConnectorTypeEnum :: TYPE_2 ) -> first(),
        ]
      );

    factory( ChargingPrice :: class ) -> create(
      [
        'min_kwt'                   => 0,
        'max_kwt'                   => 5,
        'start_time'                => '00:00',
        'end_time'                  => '24:00',
        'price'                     => 0,
        'charger_connector_type_id' => $chargerConnectorType -> id,
      ]
    ); 

    factory( ChargingPrice :: class ) -> create(
      [
        'min_kwt'                   => 6,
        'max_kwt'                   => 10,
        'start_time'                => '00:00',
        'end_time'                  => '24:00',
        'price'                     => 10,
        'charger_connector_type_id' => $chargerConnectorType -> id,
      ]
    ); 

    factory( ChargingPrice :: class ) -> create(
      [
        'min_kwt'                   => 11,
        'max_kwt'                   => 1000000,
        'start_time'                => '00:00',
        'end_time'                  => '24:00',
        'price'                     => 20,
        'charger_connector_type_id' => $chargerConnectorType -> id,
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

  private function make_charger_free()
  {
    if( ! MishasCharger :: isChargerFree( 29 ))
    {
      Simulator :: upAndRunning( 29 );
      Simulator :: plugOffCable( 29 );
      sleep( 3 );
    }
  }

  /**
   * No difference in time of day.
   * 
   * 1. 0   => 5          --> 50
   * 1. 6   => 20         --> 70
   * 1. 21  => 100000000  --> 95
   * 
   * @return \App\Order
   */
  private function set_charging_prices()
  {
    User :: truncate();
    $this -> create_user_and_return_token();

    $user                 = User :: first();
    $userCard             = factory( UserCard :: class ) -> create([ 'user_id' => $user -> id ]);
    $connectorType        = ConnectorType :: whereName( ConnectorTypeEnum :: TYPE_2 ) -> first();
    $chargerConnectorType = factory( ChargerConnectorType :: class ) -> create(
      [
        'connector_type_id' => $connectorType -> id,
      ]
    );
    
    
    factory( ChargingPrice :: class ) -> create(
      [
        'charger_connector_type_id' => $chargerConnectorType -> id,
        'min_kwt'                   => 0,
        'max_kwt'                   => 5,
        'start_time'                => '00:00',
        'end_time'                  => '24:00',
        'price'                     => 50,
      ]
    );

    factory( ChargingPrice :: class ) -> create(
      [
        'charger_connector_type_id' => $chargerConnectorType -> id,
        'min_kwt'                   => 6,
        'max_kwt'                   => 20,
        'start_time'                => '00:00',
        'end_time'                  => '24:00',
        'price'                     => 70,
      ]
    );
    
    factory( ChargingPrice :: class ) -> create(
      [
        'charger_connector_type_id' => $chargerConnectorType -> id,
        'min_kwt'                   => 21,
        'max_kwt'                   => 10000000,
        'start_time'                => '00:00',
        'end_time'                  => '24:00',
        'price'                     => 95,
      ]
    );


    $order    = factory( Order :: class )   -> create(
      [
        'charger_connector_type_id' => $chargerConnectorType -> id,
        'user_id'                   => $user                 -> id, 
        'user_card_id'              => $userCard             -> id,
        'target_price'              => null,
      ]
    );

    $order -> kilowatt() -> create(
      [
        'consumed'        => 0,
        'charging_power'  => 0,
      ]
    );
    
    return $order;
  }

  /**
   * Make fast charging prices.
   * 
   * --------------------------------------
   * start_minutes => end_minutes => price
   * --------------------------------------
   * 0             => 10          => 1
   * --------------------------------------
   * 11            => 20          => 2
   * --------------------------------------
   * 21            => 10000000    => 5
   * --------------------------------------
   * 
   * @param int $chargerConnectorTypeId
   */
  public function make_fast_charging_prices( $chargerConnectorTypeId )
  {
    factory( FastChargingPrice :: class ) -> create(
      [
        'start_minutes'             => 1,
        'end_minutes'               => 10,
        'price'                     => 1,
        'charger_connector_type_id' => $chargerConnectorTypeId,
      ]
    );

    factory( FastChargingPrice :: class ) -> create(
      [
        'start_minutes'             => 11,
        'end_minutes'               => 20,
        'price'                     => 2,
        'charger_connector_type_id' => $chargerConnectorTypeId,
      ]
    );

    factory( FastChargingPrice :: class ) -> create(
      [
        'start_minutes'             => 21,
        'end_minutes'               => 1000000,
        'price'                     => 5,
        'charger_connector_type_id' => $chargerConnectorTypeId,
      ]
    );
  }
}