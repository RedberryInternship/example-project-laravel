<?php

namespace Tests\Unit\V2\BillingCalculator;

use Tests\TestCase;

use App\Enums\PaymentType as PTEnum;
use App\Enums\OrderStatus as OSEnum;

use Tests\Unit\V2\Stubs\ChargerConnectorType as CCTStub;
use Tests\Unit\V2\Stubs\ChargingPrice as CPStub;

use App\User;
use App\Order;
use App\Payment;

class LVL2Calculator extends TestCase
{
  private $uri;
  private $active_orders_url;
  private $user;
  private $order;

  protected function setUp(): void
  {
    parent :: setUp();
    $this -> artisan( 'migrate:fresh' );

    $this -> uri               = config( 'app' )[ 'uri' ];
    $this -> active_orders_url = $this -> uri . 'active-orders';
    $this -> user              = factory( User   :: class ) -> create();
    $this -> createOrderWithPrerequisites();
  }

  /** @test */
  public function it_can_count_paid_money()
  {
    $order =  $this -> order;

    factory( Payment :: class ) -> create(
      [ 
        'order_id' => $order -> id,
        'type'      => PTEnum :: CUT,
        'price'     => 3.7125,  
      ]
    );
    
    factory( Payment :: class ) -> create(
      [ 
        'order_id' => $order -> id,
        'type'      => PTEnum :: CUT,
        'price'     => 5.8,  
      ]
    );
    
    factory( Payment :: class ) -> create(
      [ 
        'order_id' => $order -> id,
        'type'      => PTEnum :: FINE,
        'price'     => 120.9,  
      ]
    );

    $response = $this -> actAs( $this -> user ) -> get( $this -> active_orders_url );

    $response = ( object ) $response -> decodeResponseJson(0); 
   
    $this -> assertEquals( 9.51, $response -> already_paid );
  }

  /** Helpers */
  private function createOrderWithPrerequisites()
  {
    $chargerConnectorType = CCTStub :: createChargerConnectorType();

    CPStub :: createChargingPricesWithOnePhaseOfDay( $chargerConnectorType -> id );

    $this -> order             = factory( Order  :: class ) -> create(
      [ 
        'user_id'                   => $this -> user -> id,
        'charger_connector_type_id' => $chargerConnectorType -> id,
      ]
    );
    $this -> order -> kilowatt() -> create([ 'consumed' => 100, 'charging_power' => 1000 ]);
    $this -> order -> updateChargingStatus( OSEnum :: CHARGING );
  }
}