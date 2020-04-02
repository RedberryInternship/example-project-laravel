<?php

namespace App\Library\Chargers;

use App\ConnectorType;
use App\Facades\Charger;
use App\Charger as OurCharger;
use App\Library\Testing\MishasMockCharger;
use Faker\Generator as Faker;

class Sync
{


  /**
   * Existing Charger Connector Types.
   *
   * @var array<string>
   */
  private $connectorTypes = [];
  
  
  
  /**
   * Our Database Chargers Collection
   * 
   * @var App\Charger
   */
  private $ourChargers;

  /**
   * Faker instance.
   * 
   * @var Faker\Generator
   */
  private $faker;

  
  /**
   * @param App\ConnectorType $connector_types
   * @param App\Charger $ourChargers
   * @return void
   */
  public function __construct(ConnectorType $connector_types, OurCharger $ourChargers, Faker $faker)
  {
    $connector_types = $connector_types -> all();

    foreach($connector_types as $conn_type){
      $this -> connectorTypes[ strtolower($conn_type -> name) ] = $conn_type -> id;
    }

    $this -> ourChargers = $ourChargers -> with('connector_types') -> get();
    $this -> faker = $faker;
  }

  /**
   * Insert or update existing charger records in 
   * database with Misha's Chargers
   * 
   * @return void
   */
  public function insertOrUpdate()
  {

    $mishasChargers = $this -> getAllChargers();
    
    $this -> insertOrUpdateChargers($mishasChargers);
  }

  /**
   * Insert or update one.
   * 
   * @param object $charger_id
   * 
   * @return void
   */
  public function insertOrUpdateOne($charger_id)
  {
    $m_charger = $this -> getCharger($charger_id);

    $parsed_connectors = $this -> parseConnectors($m_charger -> connectors);
    $m_charger = $this -> parseCharger($m_charger);

    $this -> insertOrUpdateSingleCharger($m_charger, $parsed_connectors);
  }

  /**
   * insert or update with custom Mock Misha's chargers
   * 
   * @param array<App\Library\Testing\MishasMockCharger> $mockChargers
   */
  public function mockInsertOrUpdate($mockChargers = null)
  {

    if(!$mockChargers)
    {
      $n = random_int(10, 20);
      $mockChargers = $this -> generateMockChargers($n);
    }

    $this -> insertOrUpdateChargers($mockChargers);
  }

  /**
   * Mock insert or update one
   * 
   * @param object $m_charger
   */
  public function mockInsertOrUpdateOne($m_charger)
  {
    $parsed_connectors = $this -> parseConnectors($m_charger -> connectors);
    $m_charger = $this -> parseCharger($m_charger);

    $this -> insertOrUpdateSingleCharger($m_charger, $parsed_connectors);
  }

  /**
   * Generate mock chargers
   * 
   * @param int $numberOfInstances
   * @return array<App\Library\Testing\MishasMockCharger> 
   */
  public function generateMockChargers($numberOfInstances)
  {
    $mockChargers = [];
    while($numberOfInstances--){
      $mockChargers []= $this -> generateSingleMockCharger();
    }

    return $mockChargers;
  }

  /**
   * Generate new mock charger
   * 
   * @return App\Library\Testing\MishasMockCharger
   */
  public function generateSingleMockCharger() 
  {
    return new MishasMockCharger($this -> faker);
  }


  /**
   * Insert or update existing charger records in database
   * 
   * @param array<object> $mishasChargers
   */
  private function insertOrUpdateChargers($mishasChargers)
  {
    $parsedChargers = $this -> structuredChargers($mishasChargers);
    $parsedConnectors = $this -> structuredConnectors($mishasChargers);

    foreach($parsedChargers as $charger)
    {
     $this -> insertOrUpdateSingleCharger(
        $charger,
        $parsedConnectors[$charger['charger_id']],
      ); 
    }
  }

  /**
   * Insert or update single charger
   * 
   * @param array $charger
   * @param array $charger_parsed_connectors
   * @return void
   */
  private function insertOrUpdateSingleCharger($charger, $charger_parsed_connectors){
    $db_charger = $this -> getChargerFromLoadedCollection($charger['charger_id']);

    if($db_charger)
      {
        $areChargerAttributesUpdated = $this -> isChargerUpdated($db_charger, $charger);
        $areChargerConnectorsUpdated = $this 
          -> areConnectorsUpdated(
              $charger['charger_id'],
              $charger_parsed_connectors,
            );

        $areChargerAttributesUpdated 
          && $db_charger -> update($charger);

        $areChargerConnectorsUpdated 
          && $this -> updateChargerConnectors(
            $db_charger, 
            $charger_parsed_connectors
          );
      }
      else
      {
        $this -> insertNewChargerWithConnectors($charger, $charger_parsed_connectors);
      }
  }


  /**
   * Get All the chargers from Misha's Database.
   * 
   * @return array<object>
   */
  private function getAllChargers()
  {  
    $response = Charger::all(); 
    return $response['data'] -> data -> chargers;
  }

  /**
   * Get specific charger from Misha's Database.
   * 
   * @param int $id
   * @return array
   */
   private function getCharger($id){
      $response = Charger::find($id);
      return $response['data'] -> data; 
   }



  /**
   * Structure retrieved chargers for insertion(or update)
   * 
   * @return array<array>
   */
  private function structuredChargers($chargers)
  {
    $data_to_insert = [];
    
    foreach($chargers as $charger){
      $data_to_insert []= $this -> parseCharger($charger);
    }

    return $data_to_insert;
  } 

  /**
   * Parse each Misha's charger data into insertable record.
   * 
   * @return array
   */
  private function parseCharger($charger)
  {  
    $is_charger_active = $charger -> status == -1 ? false : true;

    return [
      'charger_id' => (int) $charger -> id,
      'code' => $charger -> code,
      'description' => $charger -> description,
      'active' => $is_charger_active,
      'lat' => $charger -> latitude,
      'lng' => $charger -> longitude,
    ]; 
  }


  /**
   * Find chargers in retrieved chargers collection in our database
   * 
   * @param  int  $id
   * @return App\Charger
   */
  private function getChargerFromLoadedCollection($id)
  {
      $db_charger = $this -> ourChargers 
        -> where('charger_id', $id)
        ->first();
      return $db_charger;
  }


  /**
   * Find out if charger is updated in Misha's back or not
   * 
   * @param App\Charger $db_charger
   * @param array $m_charger
   */
  private function isChargerUpdated($db_charger, $m_charger)
  {
    return $db_charger -> charger_id != $m_charger['charger_id']
          || $db_charger -> code != $m_charger['code'] 
          || $db_charger -> description != $m_charger['description'] 
          || $db_charger -> active != $m_charger['active'] 
          || $db_charger -> lat != $m_charger['lat'] 
          || $db_charger -> lng != $m_charger['lng'];

  }

  /**
   * Find out if connectors are updated.
   * 
   * @param int $charger_id
   * @param array $connectors
   * @return bool
   */
  private function areConnectorsUpdated($charger_id, $connectors)
  {
    $old_connectors = $this 
      -> ourChargers 
      -> where('charger_id', $charger_id) 
      -> first() 
      -> connector_types
      -> pluck('name')
      -> all();
    
    $old_connectors = array_map('strtolower', $old_connectors);
    sort($old_connectors);

    $new_connectors = array_map('strtolower', $connectors);
    sort($new_connectors);


    return $old_connectors != $new_connectors;
  }

  /**
   * Structure connectors from misha's chargers 
   * in a way to easily insert them into db.
   * 
   * @param array<object> $m_chargers
   * 
   * @return array
   */

  private function structuredConnectors($m_chargers)
  {
    $structuredConnectors = [];
    foreach($m_chargers as $charger)
    {
      $structuredConnectors [$charger -> id]= $this -> parseConnectors($charger -> connectors);
    }

    return $structuredConnectors;
  }

  /**
   * Parse each connector from Misha's chargers
   * 
   * @param object $conn
   * @return array
   */
  private function parseConnectors($conns)
  {
    $connector_types = [];
    foreach($conns as $conn)
    {
      $connector_types []= $conn -> type;
    }

    return $connector_types;
  }

  /**
   * Check if this connector from Misha's Chargers exits, 
   * and if not add into the database
   * 
   * @param string $connector
   * @return void
   */
  private function addConnectorIfNotAdded($connector)
  {
    $connector = strtolower($connector);

    $conn_types = array_keys($this -> connectorTypes);
    $is_in_array = in_array($connector, $conn_types);

    if(!$is_in_array){
      $new_connector_type = ConnectorType::create([
        'name' => $connector
      ]);

      $this -> connectorTypes[$connector] = $new_connector_type -> id;
    }
  }

  /**
   * Update charger connectors
   * 
   * @param App\Charger $db_charger
   * @param array $connectors
   * 
   * @return void
   */
   private function updateChargerConnectors($db_charger, $connectors)
   {
    $existingConnTypeIds = $db_charger 
            -> connector_types
            -> pluck('id')
            -> all();

    $db_charger 
      -> connector_types()
      -> updateExistingPivot($existingConnTypeIds, ['status' => 'inactive']);
    
    foreach($connectors as $connector){
      
      $connector = strtolower($connector);
      $this -> addConnectorIfNotAdded($connector);
      
      $db_charger
        -> connector_types()
        -> attach($this -> connectorTypes[strtolower($connector)]);
    }
   }


   /**
    * Insert new charger with connectors
    * 
    * @param array $charger
    * @param array $connectors
    * @return void
    */
   private function insertNewChargerWithConnectors($charger, $connectors)
   {
    $newCharger = OurCharger::create($charger);
    foreach($connectors as $connector)
    { 
      $connector = strtolower($connector);
      $this -> addConnectorIfNotAdded($connector);
      $newCharger -> connector_types() -> attach( $this -> connectorTypes[$connector] );
    }
   }

}