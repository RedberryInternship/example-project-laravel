<?php

namespace Tests\Unit;

use App\ChargerConnectorType;
use App\Enums\OrderStatus;
use App\Order as AppOrder;
use App\ChargingPrice;
use Tests\TestCase;
use App\Kilowatt;
use App\Company;
use App\Charger;
use App\Config;

class Order extends TestCase
{
  protected function setUp(): void
  {
    parent :: setUp();

    $this -> activeOrdersURL        = $this -> uri . 'active-orders';
    $this -> getSingleOrder         = $this -> uri . 'order';
    $this -> transactionsHistoryURL = $this -> uri . 'transactions-history';

    $this -> user   = $this -> createUser();
    $this -> company              = factory( Company :: class ) -> create();
    $this -> charger              = factory( Charger :: class ) -> create(
      [
        'company_id'  => $this -> company -> id,
        'code'        => '0028',
      ]
    );
    $this -> chargerConnectorType = factory( ChargerConnectorType :: class ) -> create(
      [
        'charger_id' => $this -> charger -> id,
        'connector_type_id' => 1,
      ]
    );

    $this -> orders = factory( AppOrder :: class, 2 ) -> create(
      [
        'user_id' => $this -> user -> id,
      ]
    )
    -> each(function($order) {
      factory( Kilowatt:: class ) -> create(
        [
          'order_id' => $order -> id,
        ]
      );

      $chargerConnectorType = factory( ChargerConnectorType :: class ) -> create(
        [
          'charger_id'        => $this -> charger -> id,
          'connector_type_id' => 1,
        ]
      );
  
      factory( ChargingPrice :: class ) -> create(
        [
          'charger_connector_type_id' => $chargerConnectorType -> id,
          'min_kwt'  => 0,
          'max_kwt'  => 10000,
          'start_time'  => '00:00',
          'end_time'    => '24:00',
        ]
      );
      
      $order -> charger_connector_type_id = $chargerConnectorType->id;
      $order -> save();
    });

    factory( Config :: class ) -> create();
  }

  /** @test */
  public function active_orders_ok(): void
  {
    $this 
      -> actAs( $this -> user )
      -> get( $this -> activeOrdersURL )
      -> assertOk()
      -> assertJsonCount(2);
  }

  /** @test */
  public function get_specific_order(): void
  {
    $this
      -> actAs( $this -> user )
      -> get( $this -> getSingleOrder . '/' . $this -> orders -> first() -> id )
      -> assertOk();
  }
  
  /** @test */
  public function get_specific_order_has_validation_errors(): void
  {
    $this
      -> actAs( $this -> user )
      -> get( 1011 )
      -> assertStatus(404);
  }

  /** @test */
  public function get_user_transactions_history_gives_ok(): void
  {
    $this
      -> actAs( $this -> user )
      -> get( $this -> transactionsHistoryURL )
      -> assertOk();
  }
  
  /** @test */
  public function get_user_transactions_history_gives_exact_count(): void
  {
    $this -> orders = factory( AppOrder :: class, 7 ) -> create(
      [
        'user_id'         => $this -> user -> id,
        'charging_status' => OrderStatus :: FINISHED,
      ]
    );

    $data = $this
      -> actAs( $this -> user )
      -> get( $this -> transactionsHistoryURL )
      -> decodeResponseJson();

    if(isset($data['data']))
    {
      $this -> assertCount(7, $data['data']);
    }
    else
    {
      $this -> assertCount(7, $data);
    }
  }
}