<?php

namespace Tests\Unit\V2\Orders;

use Illuminate\Foundation\Testing\RefreshDatabase;

use Tests\TestCase;

use App\Enums\OrderStatus as OrderStatusEnum;

use App\UserCard;
use App\Payment;
use App\Order;
use App\User;

class Entity extends TestCase
{
  use RefreshDatabase;

  private $user;
  private $order;

  protected function setUp(): void
  {
    parent::setUp();

    $this -> user   = factory( User :: class ) -> create();
    $this -> order  = factory( Order :: class ) -> create([ 'user_id' => $this -> user -> id ]);
  }


  /** @test */
  public function it_can_get_only_confirmed_payments()
  {
    factory( Payment :: class ) -> create([ 'order_id' => $this -> order -> id, 'confirmed' => true ]);
    factory( Payment :: class ) -> create([ 'order_id' => $this -> order -> id, 'confirmed' => true ]);
    factory( Payment :: class ) -> create([ 'order_id' => $this -> order -> id, 'confirmed' => true ]);
    factory( Payment :: class ) -> create([ 'order_id' => $this -> order -> id, 'confirmed' => false ]);
    factory( Payment :: class ) -> create([ 'order_id' => $this -> order -> id, 'confirmed' => false ]);

    $order = Order :: confirmedPayments() -> find( $this -> order -> id );

    $this -> assertCount( 3, $order -> payments );
  }

  /** @test */
  public function it_can_get_only_confirmed_payments_with_user_cards()
  {
    $userCard = factory( UserCard :: class ) -> create([ 'user_id' => $this -> user -> id ]);

    factory( Payment :: class, 3 ) -> create(
      [ 
        'order_id'     => $this -> order -> id, 
        'confirmed'    => true,
        'user_card_id' => $userCard -> id
      ]
    );

    factory( Payment :: class, 2 ) -> create(
      [
        'order_id'     => $this -> order -> id,
        'confirmed'    => false,
        'user_card_id' => $userCard -> id,
      ]
    );

    $order = Order :: confirmedPaymentsWithUserCards() -> find( $this -> order -> id );
    
    $this -> assertCount( 3, $order -> payments );
    $this -> assertTrue( !! $order -> payments -> first() -> user_card );
  }

   /** @test */
   public function it_sets_charging_status_change_dates_in_the_start()
   {
     $order = $this -> order;
 
     $order -> updateChargingStatus( OrderStatusEnum :: CHARGED );
 
     $chargingStatusChangeDates = $order -> charging_status_change_dates;
 
     $structure    = array_keys( $chargingStatusChangeDates );
     $allStatuses  = OrderStatusEnum :: getConstantsValues();
     
     sort( $structure   );
     sort( $allStatuses );
 
     $this -> assertEquals( $structure, $allStatuses );
   }


  /** @test */
  public function it_can_update_charging_status()
  {
    $order = $this -> order;

    $order  -> charging_status = OrderStatusEnum :: INITIATED;
    $order  -> save();

    $order  -> refresh();

    $this   -> assertEquals( $order -> charging_status, OrderStatusEnum :: INITIATED );
    
    $order  -> updateChargingStatus( OrderStatusEnum :: ON_FINE );
    $this   -> assertEquals( $order -> charging_status, OrderStatusEnum :: ON_FINE );
    
    $order  -> refresh();
    $this   -> assertEquals( $order -> charging_status, OrderStatusEnum :: ON_FINE );
  }

  /** @test */
  public function it_can_determine_if_it_is_on_penalty()
  {
    $order = $this -> order;
    $this   -> assertTrue( ! $order -> isOnPenalty() );

    $order  -> updateChargingStatus( OrderStatusEnum :: ON_FINE );
    $this   -> assertTrue(   $order -> isOnPenalty() );
  }
}