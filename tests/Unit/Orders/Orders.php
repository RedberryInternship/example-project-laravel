<?php

namespace Tests\Unit\Orders;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use App\Facades\MockSyncer;

use App\Order;
use App\Charger;

class Orders extends TestCase{
  use RefreshDatabase;

  private $uri;
  private $update_url;
  private $stop_url;

  protected function setUp(): void
  {
    parent::setUp();
    
    $this -> uri        = config( 'app' )['uri'];
    $this -> update_url = '/chargers/transactions/update/';
    $this -> stop_url   = '/chargers/transactions/stop/';
  }


    /** @test */
    public function charger_transaction_returns_valid_unfinished_transactions()
    {
      DB :: table( 'charger_transactions' ) -> insert([
        [
          'charger_id'          => 1,
          'connector_type_id'   => 1,
          'm_connector_type_id' => 1,
          'transactionID'       => 1,
          'status'              => 'INITIATED',
          'created_at'          => now(),
          'updated_at'          => now(),
        ],
        [
          'charger_id'          => 1,
          'connector_type_id'   => 2,
          'm_connector_type_id' => 2,
          'transactionID'       => 2,
          'status'              => 'CHARGING',
          'created_at'          => now(),
          'updated_at'          => now(),
        ],
        [
          'charger_id'          => 3,
          'connector_type_id'   => 1,
          'm_connector_type_id' => 1,
          'transactionID'       => 3,
          'status'              => 'CHARGED',
          'created_at'          => now(),
          'updated_at'          => now(),
        ],
        [
          'charger_id'          => 4,
          'connector_type_id'   => 1,
          'm_connector_type_id' => 1,
          'transactionID'       => 4,
          'status'              => 'FINISHED',
          'created_at'          => now(),
          'updated_at'          => now(),
        ]
      ]);

      $charger1 = MockSyncer :: generateSingleMockCharger();
      $charger2 = MockSyncer :: generateSingleMockCharger();
      $charger3 = MockSyncer :: generateSingleMockCharger();
      $charger4 = MockSyncer :: generateSingleMockCharger();
      
      MockSyncer :: insertOrUpdate([
        $charger1,
        $charger2,
        $charger3,
        $charger4,
      ]);

      $free_chargers_ids        = Order :: getFreeChargersIds();
      $is_charger_of_id_1_free  = Order :: isChargerFree(1);
      
      $this -> assertEquals( 2, count( $free_chargers_ids ));
      $this -> assertTrue( $is_charger_of_id_1_free );
    }

    /** @test */
    public function charger_transaction_method_for_getting_latest_kilowatt_update_works()
    {
      $transaction_ID           = '19815';
      $mishas_mock_charger_data = MockSyncer :: generateSingleMockCharger();
      
      MockSyncer :: insertOrUpdate([ $mishas_mock_charger_data ]);

      $charger                = Charger::with( 'connector_types' ) -> first();
      $charger_connector_type = $charger -> connector_types -> first();      

      $charger_transaction = Order :: create([
        'charger_id'          => $charger -> id,
        'connector_type_id'   => $charger_connector_type -> pivot -> connector_type_id,
        'm_connector_type_id' => $charger_connector_type -> pivot -> m_connector_type_id,
        'transactionID'       => $transaction_ID,
        'status'              => 'INITIATED',
      ]);
      
      $this -> get($this -> update_url . $transaction_ID . '/'. 10 );
      $this -> get($this -> update_url . $transaction_ID . '/'. 20 );
      $this -> get($this -> update_url . $transaction_ID . '/'. 27 );

      $last_consumed_kilowatt = $charger_transaction -> getLastConsumedKilowatt() -> value;
      $consumed_kilowatts     = $charger_transaction -> consumedKilowatts();
      
      $this -> assertEquals( $last_consumed_kilowatt, 27 );
      $this -> assertCount( 3, $consumed_kilowatts );
    }
}