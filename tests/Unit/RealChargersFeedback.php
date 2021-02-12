<?php

namespace Tests\Unit;

use App\Order;
use App\Config;
use App\Charger;
use App\Company;
use App\Kilowatt;
use App\UserCard;
use Tests\TestCase;
use App\Enums\OrderStatus;
use App\ChargerConnectorType;
use App\ChargingPrice;

class RealChargersFeedback extends TestCase
{
  protected function setUp(): void
  {
    parent :: setUp();

    $this -> updateURL = 'chargers/transactions/update';
    $this -> finishURL = 'chargers/transactions/finish';

    $this -> user                 = $this -> createUser();
    $this -> company              = factory( Company :: class ) -> create();
    $this -> charger              = factory( Charger :: class ) -> create(
      [
        'company_id'  => $this -> company -> id,
        'code'        => '0028',
      ]
    );
    
    $this -> chargerConnectorType = factory( ChargerConnectorType :: class ) -> create(
      [
        'charger_id'        => $this -> charger -> id,
        'connector_type_id' => 1,
      ]
    );

    factory( ChargingPrice :: class ) -> create(
      [
        'charger_connector_type_id' => $this -> chargerConnectorType -> id,
        'min_kwt'  => 0,
        'max_kwt'  => 10000,
        'start_time'  => '00:00',
        'end_time'    => '24:00',
      ]
    );

    $this -> userCard             = factory( UserCard :: class ) -> create(
      [
        'user_id' => $this -> user -> id,
      ]
    );
    
    $this -> order = factory( Order :: class ) -> create(
      [
        'charger_connector_type_id' => $this -> chargerConnectorType -> id,
        'user_card_id'              => $this -> userCard -> id,
        'user_id'                   => $this -> user -> id,
        'charging_status'           => OrderStatus :: CHARGING,
      ]
    );

    factory( Kilowatt :: class ) -> create(
      [
        'order_id' => $this -> order -> id,
      ]
    );

    factory( Config :: class ) -> create();
  }

  /** @test */
  public function order_gets_finished_when_disconnected(): void
  {
    $this
      -> get( $this -> finishURL . '/' . $this -> order -> charger_transaction_id )
      -> assertOk();

    $this -> order -> refresh();
    $this -> assertEquals( OrderStatus :: FINISHED, $this -> order -> charging_status );
  }

  /** @test */
  public function order_gets_updated(): void
  {
    $this 
      -> get($this -> updateURL . '/' . $this -> order -> charger_transaction_id . '/' . 7)
      -> assertOk();
  
    
    $kilowatt = $this -> order -> kilowatt -> refresh();
    $consumedKilowatt = $kilowatt -> consumed * 1000;
    
    $this -> assertEquals($consumedKilowatt, 7);
  }
}