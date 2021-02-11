<?php

namespace Tests\Unit;

use App\Enums\OrderStatus;
use App\Order as AppOrder;
use Tests\TestCase;

class Order extends TestCase
{
  protected function setUp(): void
  {
    parent :: setUp();

    $this -> activeOrdersURL        = $this -> uri . 'active-orders';
    $this -> getSingleOrder         = $this -> uri . 'order';
    $this -> transactionsHistoryURL = $this -> uri . 'transactions-history';

    $this -> user   = $this -> createUser();
    $this -> orders = factory( AppOrder :: class, 2 ) -> create(
      [
        'user_id' => $this -> user -> id,
      ]
    );
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