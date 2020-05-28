<?php

namespace Tests\Unit\V2\Chargers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

use App\Charger;
use App\ConnectorType;

use App\Facades\ChargerSyncer;
use App\Facades\MockSyncer;
use App\Facades\Charger as RealCharger;

class Sync extends TestCase
{
  use RefreshDatabase;

  protected function tearDown(): void
  {
    $this -> beforeApplicationDestroyed( function (){
      foreach( DB :: getConnections() as $connection )
      {
        $connection -> disconnect();
      }
    });

    parent :: tearDown();
  }

  /** @test */
  public function do_we_get_the_chargers()
  {
    $chargers = RealCharger :: all();

    $this -> assertTrue(!!$chargers);
    $this -> assertTrue(count($chargers) > 0);
  }

  /** @test */
  public function data_can_be_inserted()
  {
    ChargerSyncer::insertOrUpdate();
    $this -> assertTrue( Charger :: count() > 0);
  }

  /** @test */
  public function mass_insert_and_update_works()
  {
    $CUSTOM_CHARGER_DESCRIPTION = 'UPDATED_DESCRIPTION';
    
    ChargerSyncer::insertOrUpdate();

    $charger_id       = $this -> getRandomChargerIdFromDB();
    $notYetExistingId = $this -> getNonExistingId();
    $count_chargers   = Charger :: count();

    /**
     * Make first mock charger that is going to
     * update existing one.
     */
    $newCharger1                = MockSyncer::generateSingleMockCharger();
    $newCharger1 -> id          =  $charger_id;
    $newCharger1 -> description = $CUSTOM_CHARGER_DESCRIPTION;
    
    /**
     * Make another mock charger that is going to be inserted.
     */
    $newCharger2        = MockSyncer::generateSingleMockCharger();
    $newCharger2 -> id  = $notYetExistingId;

    MockSyncer::insertOrUpdate([
      $newCharger1,
      $newCharger2,
    ]);

    $updatedCharger = Charger::where('charger_id', $charger_id) -> first(); 
    
    $this -> assertEquals($updatedCharger -> description , $newCharger1 -> description);
    $this -> assertEquals($count_chargers + 1, Charger::count());
  }

  /** @test */
  public function when_inserting_charger_with_nonExisting_connector_it_should_be_added_automatically()
  {
    ChargerSyncer::insertOrUpdate();
    
    $NEW_CONNECTOR = 'FateZero';
    
    $newCharger                 = MockSyncer::generateSingleMockCharger();
    $newCharger -> id           = $this -> getNonExistingId();
    $newCharger -> connectors []= $this -> makeNewConnectorObject($NEW_CONNECTOR);
    
    MockSyncer::insertOrUpdate([
      $newCharger,
    ]);

    $is_new_connector_in_db = in_array(strtolower($NEW_CONNECTOR), ConnectorType::pluck('name')->all());
    
    $this -> assertTrue($is_new_connector_in_db);
  }

  /** @test */
  public function connectors_should_be_updated()
  {
    ChargerSyncer::insertOrUpdate();
    
    $NEW_CONNECTOR1     = 'Maker 2';
    $NEW_CONNECTOR1_ID  = 7;

    $NEW_CONNECTOR2     = 'Starscrim 1';
    $NEW_CONNECTOR2_ID  = 8;

    $updated_charger_id = $this ->getRandomChargerIdFromDB();
    
    $old_charger_connectors_count = Charger::with('connector_types')
      -> where('charger_id', $updated_charger_id) 
      -> first()
      -> connector_types
      -> count();
    
    $updated_charger = MockSyncer::generateSingleMockCharger();

    $updated_charger -> id            = $updated_charger_id;
    $updated_charger -> connectors    = [];
    $updated_charger -> connectors  []= $this -> makeNewConnectorObject($NEW_CONNECTOR2, $NEW_CONNECTOR2_ID);
    $updated_charger -> connectors  []= $this -> makeNewConnectorObject($NEW_CONNECTOR1, $NEW_CONNECTOR1_ID);

    MockSyncer::insertOrUpdate([
      $updated_charger,
    ]);

    $updated_charger_connectors = Charger::with('connector_types') 
      -> where('charger_id', $updated_charger_id) 
      -> first()
      -> connector_types
      -> pluck('name')
      -> all();
    
    $updated_charger_all_connectors_count = Charger::with('connector_types_all') 
      -> where('charger_id', $updated_charger_id) 
      -> first()
      -> connector_types_all
      -> count();

      $are_connectors_added = in_array(strtolower($NEW_CONNECTOR1), $updated_charger_connectors)
        && in_array(strtolower($NEW_CONNECTOR2), $updated_charger_connectors);

      $this -> assertTrue( $are_connectors_added );
      $this -> assertEquals( $updated_charger_all_connectors_count, $old_charger_connectors_count + count($updated_charger_connectors) );
  }

  /** @test */
  public function new_charger_can_be_inserted()
  {
    ChargerSyncer::insertOrUpdate();

    $old_chargers_count = Charger::count(); 
    $notYetExistingId   = $this -> getNonExistingId();

    $newCharger       = MockSyncer::generateSingleMockCharger();
    $newCharger -> id = $notYetExistingId;

    MockSyncer::insertOrUpdateOne($newCharger);

    $this -> assertEquals($old_chargers_count + 1, Charger::count());
  }

  /** @test */
  public function mishas_connector_type_id_gets_inserted()
  {
    $new_charger      = MockSyncer::generateSingleMockCharger();
    $new_connector    = $this -> makeNewConnectorObject('Type 2');

    $new_charger_id   = $new_charger -> id;
    $new_connector_id = $new_connector -> id;

    $new_charger -> connectors   = [];
    $new_charger -> connectors []= $new_connector;

    MockSyncer::insertOrUpdateOne($new_charger);

    $m_connector_type_id = Charger::with('connector_types') 
      -> where('charger_id', $new_charger_id) 
      -> first() 
      -> connector_types 
      -> first() 
      -> pivot 
      -> m_connector_type_id;


    $this -> assertEquals($m_connector_type_id, $new_connector_id);
  }


  /** <========> Helper Function <========> */


  /**
   * Get random charger record id
   * 
   * @return int $id
   */
  private function getRandomChargerIdFromDB()
  {
    return Charger::inRandomOrder() -> first() -> charger_id;
  }

  /**
   * Get non-existing Charger -> charger_id from DB
   * 
   * @return int
   */
  private function getNonExistingId()
  {
    $notYetExistingId = 0;
    $existingIds      = Charger::pluck('charger_id')->all();
    
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
  public function makeNewConnectorObject($connector_name, $connector_id = null)
  {
    $new_connector = [
      'id'    => $connector_id ?? random_int(3, 10),
      'type'  => $connector_name,
    ];

    return (object) $new_connector;
  }
}