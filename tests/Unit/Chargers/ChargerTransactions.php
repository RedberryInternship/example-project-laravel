<?php

namespace Tests\Unit\Chargers;

use App\ChargerTransaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use App\Facades\MockSyncer;


class ChargerTransactions extends TestCase{
    use RefreshDatabase;



    /** @test */

    public function charger_transaction_returns_valid_unfinished_transactions()
    {
      DB::table('charger_transactions') -> insert([
        [
          'charger_id' => 1,
          'connector_type_id' => 1,
          'm_connector_type_id' => 1,
          'transactionID' => 1,
          'status' => 'INITIATED',
          'created_at' => now(),
          'updated_at' => now(),
        ],
        [
          'charger_id' => 1,
          'connector_type_id' => 2,
          'm_connector_type_id' => 2,
          'transactionID' => 2,
          'status' => 'CHARGING',
          'created_at' => now(),
          'updated_at' => now(),
        ],
        [
          'charger_id' => 3,
          'connector_type_id' => 1,
          'm_connector_type_id' => 1,
          'transactionID' => 3,
          'status' => 'CHARGED',
          'created_at' => now(),
          'updated_at' => now(),
        ],
        [
          'charger_id' => 4,
          'connector_type_id' => 1,
          'm_connector_type_id' => 1,
          'transactionID' => 4,
          'status' => 'FINISHED',
          'created_at' => now(),
          'updated_at' => now(),
        ]
      ]);

      $charger1 = MockSyncer::generateSingleMockCharger();
      $charger2 = MockSyncer::generateSingleMockCharger();
      $charger3 = MockSyncer::generateSingleMockCharger();
      $charger4 = MockSyncer::generateSingleMockCharger();
      
      MockSyncer::insertOrUpdate([
        $charger1,
        $charger2,
        $charger3,
        $charger4,
      ]);

      $free_chargers_ids = ChargerTransaction::getFreeChargersIds();
      $is_charger_of_id_1_free = ChargerTransaction::isChargerFree(1);
      
      $this -> assertEquals(2, count($free_chargers_ids));
      $this -> assertTrue($is_charger_of_id_1_free);
    }
}