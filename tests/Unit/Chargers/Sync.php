<?php

namespace Tests\Unit\Chargers;

use Tests\TestCase;
use App\Facades\Charger;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Charger as OurCharger;
use App\ConnectorType;
use App\Facades\ChargerSyncer;

class Sync extends TestCase
{

  use RefreshDatabase;

  /** @test */
  public function do_we_get_the_chargers()
  {
    $response = Charger::all();

    $this -> assertTrue($response['status_code'] == 700);
    
    $chargers = $response['data']->data->chargers;
    $this -> assertTrue(count($chargers) > 0);
  }

  /** @test */
  public function data_can_be_inserted()
  {
    ChargerSyncer::insertOrUpdate();
    $this -> assertTrue(OurCharger::count() > 0);
  }

  /** @test */
  public function mass_insert_and_update_works()
  {
    $CUSTOM_CHARGER_DESCRIPTION = 'UPDATED_DESCRIPTION';
    ChargerSyncer::insertOrUpdate();

    $charger_id = $this -> getRandomChargerIdFromDB();
    $notYetExistingId = $this -> getNonExistingId();
    $count_chargers = OurCharger::count();

    /**
     * Make first mock charger that is going to
     * update existing one.
     */
    $newCharger1 = ChargerSyncer::generateSingleMockCharger();
    $newCharger1 -> id =  $charger_id;
    $newCharger1 -> description = $CUSTOM_CHARGER_DESCRIPTION;
    
    /**
     * Make another mock charger that is going to be inserted.
     */
    $newCharger2 = ChargerSyncer::generateSingleMockCharger();
    $newCharger2 -> id = $notYetExistingId;

    ChargerSyncer::mockInsertOrUpdate([
      $newCharger1,
      $newCharger2,
    ]);

    $updatedCharger = OurCharger::where('charger_id', $charger_id) -> first(); 
    
    $this -> assertEquals($updatedCharger -> description , $newCharger1 -> description);
    $this -> assertEquals($count_chargers + 1, OurCharger::count());
  }

  /** @test */
  public function when_inserting_charger_with_nonExisting_connector_it_should_be_added_automatically()
  {
    ChargerSyncer::insertOrUpdate();
    
    $NEW_CONNECTOR = 'FateZero';

    
    $newCharger = ChargerSyncer::generateSingleMockCharger();
    $newCharger -> id = $this -> getNonExistingId();
    $newCharger -> connectors []= $this -> makeNewConnectorObject($NEW_CONNECTOR);
    
    
    ChargerSyncer::mockInsertOrUpdate([
      $newCharger,
    ]);

    $is_new_connector_in_db = in_array(strtolower($NEW_CONNECTOR), ConnectorType::pluck('name')->all());
    
    $this -> assertTrue($is_new_connector_in_db);
  }

  /** @test */
  public function connectors_should_be_updated()
  {
    ChargerSyncer::insertOrUpdate();
    $NEW_CONNECTOR = 'Maker 2';

    $updated_charger_id = $this ->getRandomChargerIdFromDB();
    
    $old_charger_connectors_count = OurCharger::with('connector_types')
      -> where('charger_id', $updated_charger_id) 
      -> first()
      -> connector_types
      -> count();
    
    $updated_charger = ChargerSyncer::generateSingleMockCharger();


    $updated_charger -> id = $updated_charger_id;
    $updated_charger -> connectors []= $this -> makeNewConnectorObject($NEW_CONNECTOR);

    ChargerSyncer::mockInsertOrUpdate([
      $updated_charger,
    ]);

    $updated_charger_connectors = OurCharger::with('connector_types') 
      -> where('charger_id', $updated_charger_id) 
      -> first()
      -> connector_types
      -> pluck('name','pivot')
      -> all();
    
    $updated_charger_all_connectors_count = OurCharger::with('connector_types_all') 
      -> where('charger_id', $updated_charger_id) 
      -> first()
      -> connector_types_all
      -> count();

      $is_connector_added = in_array(strtolower($NEW_CONNECTOR), $updated_charger_connectors);

      $this -> assertTrue( $is_connector_added );
      $this -> assertEquals( $updated_charger_all_connectors_count, $old_charger_connectors_count + count($updated_charger_connectors) );
  }

  /** @test */
  public function new_charger_can_be_inserted()
  {
    ChargerSyncer::insertOrUpdate();
    $old_chargers_count = OurCharger::count(); 
    $notYetExistingId = $this -> getNonExistingId();

    $newCharger = ChargerSyncer::generateSingleMockCharger();
    $newCharger -> id = $notYetExistingId;

    ChargerSyncer::mockInsertOrUpdateOne($newCharger);

    $this -> assertEquals($old_chargers_count + 1, OurCharger::count());
  }


  /** <========> Helper Function <========> */


  /**
   * Get random charger record id
   * 
   * @return int $id
   */
  private function getRandomChargerIdFromDB()
  {
    return OurCharger::inRandomOrder() -> first() -> charger_id;
  }

  /**
   * Get non-existing Charger -> charger_id from DB
   * 
   * @return int
   */
  private function getNonExistingId()
  {
    $notYetExistingId = 0;
    $existingIds = OurCharger::pluck('charger_id')->all();
    while(in_array($notYetExistingId, $existingIds))
    {
      $notYetExistingId = random_int(1, 1000);
    }

    return $notYetExistingId;
  }

  /**
   * Create new connector object
   * 
   * @param string $connector_name
   * 
   * @return object
   */
  public function makeNewConnectorObject($connector_name)
  {
    $new_connector = [
      'id' => random_int(3, 10),
      'type' => $connector_name,
    ];

    return (object) $new_connector;
  }
}