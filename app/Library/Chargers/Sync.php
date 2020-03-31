<?php

namespace App\Library\Chargers;

use App\ConnectorType;
use App\Facades\Charger;
use App\Charger as OurCharger;

class Sync
{

  private $connectorTypes = [];
  private $ourChargers;

  public function __construct(ConnectorType $connector_types, OurCharger $ourChargers)
  {
    $connector_types = $connector_types -> all();

    foreach($connector_types as $conn_type){
      $this -> connectorTypes [$conn_type -> name] = $conn_type -> id;
    }

    $this -> ourChargers = $ourChargers -> all();
  
  }

  public function insertOrUpdate()
  {

    $mishasChargers = $this -> getAllChargers();
    
    $parsedChargers = $this -> structuredChargers($mishasChargers);
    $parsedConnectors = $this -> structuredConnectors($mishasChargers);

    foreach($parsedChargers as $charger){
      
      $db_charger = $this -> getChargerFromLoadedCollection($charger['charger_id']);

      if($db_charger){

        $isUpdated = $this -> isChargerUpdated($db_charger, $charger);
        $isUpdated && $db_charger -> update($charger);

      }
      else{
        $newCharger = OurCharger::create($charger);
        foreach($parsedConnectors[$charger['charger_id']] as $connector){
          
          $this -> addConnectorIfNotAdded($connector);

          $newCharger -> connector_types() -> attach( $this -> connectorTypes[$connector] );
        }
      }
    }
   
  }

  private function getAllChargers(){
    
    $response = Charger::all();
    
    return $response['data'] -> data -> chargers;
  }

  private function structuredChargers($chargers)
  {
    $data_to_insert = [];
    
    foreach($chargers as $charger){
      $data_to_insert []= $this -> parseCharger($charger);
    }

    return $data_to_insert;
  }

  private function parseCharger($charger){
    
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

  private function getChargerFromLoadedCollection($id){
      $db_charger = $this -> ourChargers 
        -> where('charger_id', $id)
        ->first();
      return $db_charger;
  }


  private function isChargerUpdated($db_charger, $m_charger)
  {
    return $db_charger -> charger_id != $m_charger['charger_id']
          || $db_charger -> code != $m_charger['code'] 
          || $db_charger -> description != $m_charger['description'] 
          || $db_charger -> active != $m_charger['active'] 
          || $db_charger -> lat != $m_charger['lat'] 
          || $db_charger -> lng != $m_charger['lng'];

  }

  private function structuredConnectors($chargers){
    
    $structuredConnectors = [];
    
    foreach($chargers as $charger){

      $structuredConnectors [$charger -> id]= $this -> parseConnectors($charger -> connectors);
    }

    return $structuredConnectors;
  }

  private function parseConnectors($conns){
    $connector_types = [];
    foreach($conns as $conn){
      $connector_types []= $conn -> type;
    }

    return $connector_types;
  }


  private function addConnectorIfNotAdded($connector)
  {
    $conn_types = array_keys($this -> connectorTypes);

    $is_in_array = in_array($connector, $conn_types);

    if(!$is_in_array){
      
      $new_connector_type = ConnectorType::create([
        'name' => $connector
      ]);

      $this -> connectorTypes[$connector] = $new_connector_type -> id;

    }
  }

}