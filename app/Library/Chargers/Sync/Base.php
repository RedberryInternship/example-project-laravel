<?php

namespace App\Library\Chargers\Sync;

use App\ConnectorType;
use App\Charger as OurCharger;
use Faker\Generator as Faker;

class Base
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
  protected $faker;
  
  /**
   * @param App\ConnectorType $connector_types
   * @param App\Charger $ourChargers
   * @param Faker\Generator $faker
   * 
   * @return void
   */
  public function __construct(ConnectorType $connector_types, OurCharger $ourChargers, Faker $faker)
  {
    $connector_types = $connector_types -> all();

    foreach($connector_types as $conn_type)
    {
      $this -> connectorTypes[ strtolower($conn_type -> name) ] = $conn_type -> id;
    }

    $this -> ourChargers = $ourChargers -> with('connector_types') -> get();
    $this -> faker = $faker;
  }


  /**
   * Insert or update existing charger records in database
   * 
   * @param array<object> $chargers
   */
  protected function insertOrUpdateChargers($chargers)
  {
    $parsedChargers = $this -> structuredChargers($chargers);
    $parsedConnectors = $this -> structuredConnectors($chargers);

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
  protected function insertOrUpdateSingleCharger($charger, $charger_parsed_connectors){
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
   * Structure chargers for insertion(or update)
   * 
   * @param array<object> $chargers
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
   * @param object $charger
   * @return array
   */
  protected function parseCharger($charger)
  {  
    $is_charger_active = $charger -> status == -1 ? false : true;

    return [
      'charger_id'  => (int) $charger -> id,
      'code'        => $charger -> code,
      'description' => $charger -> description,
      'active'      => $is_charger_active,
      'lat'         => $charger -> latitude,
      'lng'         => $charger -> longitude,
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
   * Find out if charger is updated or not
   * 
   * @param App\Charger $db_charger
   * @param array $m_charger
   */
  private function isChargerUpdated($db_charger, $m_charger)
  {
    return $db_charger   -> charger_id  != $m_charger['charger_id']
          || $db_charger -> code        != $m_charger['code'] 
          || $db_charger -> description != $m_charger['description'] 
          || $db_charger -> active      != $m_charger['active'] // TODO: active should be checked differently
          || $db_charger -> lat         != $m_charger['lat'] 
          || $db_charger -> lng         != $m_charger['lng'];

  }

  /**
   * Find out if connectors are updated.
   * 
   * @param int $charger_id
   * @param array<object> $connectors
   * @return bool
   */
  private function areConnectorsUpdated($charger_id, $connectors)
  {
    $old_connectors = $this 
      -> ourChargers 
      -> where('charger_id', $charger_id) 
      -> first() 
      -> connector_types
      -> pluck('name', 'pivot.m_connector_type_id')
      -> all();
    
    $old_connectors = array_map('strtolower', $old_connectors);


    array_walk($connectors, function(&$item) { $item = (array) $item; });
    $connector_ids    = array_column($connectors, 'id');
    $connector_types  = array_column($connectors, 'type');
    $new_connectors   = array_combine($connector_ids, $connector_types);
    $new_connectors   = array_map('strtolower', $new_connectors);

    ksort($old_connectors);
    ksort($new_connectors);

    return $old_connectors != $new_connectors;
  }

  /**
   * Structure connectors in a way 
   * to easily insert them into db.
   * 
   * @param array<object> $m_chargers
   * @return array
   */
  private function structuredConnectors($m_chargers)
  {
    $structuredConnectors = [];
    foreach($m_chargers as $charger)
    {
      $structuredConnectors [$charger -> id]= $charger -> connectors;
    }

    return $structuredConnectors;
  }

  /**
   * Check if this connector exits, 
   * and if not add into the database
   * 
   * @param string $connector
   * @return void
   */
  private function addConnectorIfNotAdded($connector)
  {
    $connector = strtolower($connector);

    $conn_types = array_keys($this -> connectorTypes);
    $is_not_in_array = !in_array($connector, $conn_types);

    if($is_not_in_array){
      $new_connector_type = ConnectorType::create([
        'name' => $connector
      ]);

      $this -> connectorTypes[$connector] = $new_connector_type -> id;
    }
  }

  /**
   * Update charger connectors.
   * 
   * @param App\Charger $db_charger
   * @param array $connectors
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
    
    foreach($connectors as $connector)
    {  
      $connector_type = strtolower($connector -> type);
      $this -> addConnectorIfNotAdded($connector_type);
      
      $db_charger
        -> connector_types()
        -> attach($this -> connectorTypes[$connector_type],[
          'm_connector_type_id' => $connector -> id,
        ]);
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
      $connector_type = strtolower($connector -> type);
      $this -> addConnectorIfNotAdded($connector_type);
      $newCharger -> connector_types() -> attach( $this -> connectorTypes[$connector_type], [
        'm_connector_type_id' => $connector -> id
      ]);
    }
   }
}