<?php

namespace Tests\Feature\V2;

use Tests\TestCase;

use App\Enums\OrderStatus as OrderStatusEnum;

use App\User;
use App\Order;
use App\Charger;
use App\ChargerConnectorType;

class Orders extends TestCase
{
  private $uri;
  private $user;
  private $order_url;
  
  protected function setUp(): void
  {
    parent :: setUp();
    $this -> artisan( 'migrate:fresh' );

    $this -> user               = factory( User :: class ) -> create();
    $this -> uri                = config( 'app' )[ 'uri' ];
    $this -> active_orders_url  = $this -> uri . 'active-orders';
    $this -> order_url          = $this -> uri . 'order/';
    
  }

  /** @test */
  public function it_can_get_one_order()
  {
    $charger              = factory( Charger :: class ) -> create();
    $chargerConnectorType = factory( ChargerConnectorType :: class ) -> create(
      [ 
        'charger_id'                => $charger -> id,
      ]
    );
    
    $order                = factory( Order :: class ) -> create(
      [
        'charger_connector_type_id' => $chargerConnectorType -> id,
      ]
    );

    $response = $this -> actAs( $this -> user ) -> get( $this -> order_url . $order -> id );

    $response -> assertJsonStructure(
      [
        'consumed_money',
        'already_paid',
        'refund_money',
        'charging_type',
        'charger_connector_type_id',
        'connector_type_id',
        'charger_id',
        'user_card_id',
      ]
    );
  }

  /** @test */
  public function it_returns_active_orders()
  {
    $user = $this -> user;
    factory( Order :: class )     -> create(
      [ 
        'user_id' => $user -> id, 
        'charging_status' => OrderStatusEnum :: INITIATED,
      ]
    );

    factory( Order :: class )     -> create(
      [ 
        'user_id' => $user -> id, 
        'charging_status' => OrderStatusEnum :: CHARGING, 
      ]
    );

    factory( Order :: class, 3 )  -> create(
      [ 
        'user_id' => $user -> id, 
        'charging_status' => OrderStatusEnum :: FINISHED, 
      ]
    );

    $response = $this -> actAs( $user ) -> get( $this -> active_orders_url );
    $response = $response -> decodeResponseJson();

    $this -> assertCount( 2, $response );
  }
}