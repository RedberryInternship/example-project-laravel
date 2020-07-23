<?php

namespace App\Library\Sync;

use App\ConnectorType;
use App\Charger as OurCharger;
use Faker\Generator as Faker;

use App\Library\Entities\CronJobs\RealChargersSync\ChargersParser;
use App\Library\Entities\CronJobs\RealChargersSync\ShouldUpdateChecker;

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
  protected function insertOrUpdateChargers( $realChargers )
  {
    $parsedChargers = ChargersParser :: parseAll( $realChargers );

    foreach($parsedChargers as $charger)
    {
     $this -> insertOrUpdateSingleCharger( $charger ); 
    }
  }

  /**
   * Insert or update single charger
   * 
   * @param array $charger
   * @param array $charger_parsed_connectors
   * @return void
   */
  protected function insertOrUpdateSingleCharger( $parsedRealCharger ){
    
    $localCharger = $this -> getChargerFromLoadedCollection($parsedRealCharger['charger_id']);

    if( $localCharger )
      {
        $areChargerAttributesUpdated = ShouldUpdateChecker :: checkCharger( $localCharger, $parsedRealCharger );
        $areChargerConnectorsUpdated = $this -> areConnectorsUpdated( $parsedRealCharger );

        $areChargerAttributesUpdated && $localCharger -> update( $parsedRealCharger );

        $areChargerConnectorsUpdated && $this -> updateChargerConnectors( $localCharger, $charger_parsed_connectors );
      }
      else
      {
        $this -> insertNewChargerWithConnectors($parsedRealCharger);
      }
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
    * Insert new charger with connectors
    * 
    * @param array $charger
    * @return void
    */
   private function insertNewChargerWithConnectors( $parsedRealCharger )
   {
    $connectors = $parsedRealCharger[ 'connectors' ];
    unset( $charger[ 'connectors' ] );
    $newCharger = OurCharger::create($parsedRealCharger);

    foreach($connectors as $connector)
    { 
      $connector_type = strtolower($connector -> type);
      $this       -> addConnectorIfNotAdded($connector_type);
      $newCharger -> connector_types() -> attach( $this -> connectorTypes[$connector_type], [
        'm_connector_type_id' => $connector -> id
      ]);
    }
   }
}