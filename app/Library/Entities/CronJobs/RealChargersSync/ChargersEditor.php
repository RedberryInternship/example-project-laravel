<?php

namespace App\Library\Entities\CronJobs\RealChargersSync;

use App\Library\Entities\CronJobs\RealChargersSync\ShouldUpdateChecker;
use App\Library\DataStructures\RealChargersSync as Data;

use App\ConnectorType;
use App\Charger;

class ChargersEditor
{
  /**
   * Data for synchronizing real chargers.
   * 
   * @var Data $data
   */
  private $data;

  /**
   * Set data.
   * 
   * @param Data $data
   */
  function __construct( Data $data )
  {
    $this -> data = $data;
  }

  /**
   * Synchronize real chargers with local chargers.
   * 
   * @param  Data $data
   * @return void
   */
  public static function update( Data $data )
  {
    (new self( $data )) -> scanChargers();
  }

  /**
   * Loop through each charger and update.
   * 
   * @return void
   */
  private function scanChargers()
  {
    foreach( $this -> data -> getRealChargers() as $parsedRealCharger )
    {
      $this -> scanSingleCharger( $parsedRealCharger );
    }
  }

  /**
   * Update charger.
   * 
   * @param  $parsedRealCharger
   * @return void
   */
  private function scanSingleCharger( $parsedRealCharger )
  {
    if( $this -> data -> localChargerExists( $parsedRealCharger[ 'charger_id' ] ) )
    {
      $this -> updateChargerIfChanged        ( $parsedRealCharger );
      $this -> updateConnectorsIfChanged     ( $parsedRealCharger );
    }
    else
    {
      $this -> insertNewChargerWithConnectors( $parsedRealCharger );
    }
  }

  /**
   * Update if charger attributes are changed.
   * 
   * @param  $parsedRealCharger
   * @return void
   */
  private function updateChargerIfChanged( $parsedRealCharger ): void
  {
    $localCharger = $this -> data -> getLocalCharger( $parsedRealCharger[ 'charger_id' ] );

    if( ShouldUpdateChecker :: checkCharger( $localCharger, $parsedRealCharger ))
    {
      unset( $parsedRealCharger[ 'connectors' ] );
      $localCharger -> update( $parsedRealCharger );
    }
  }

  /**
   * Update connectors if changed.
   * 
   * @param  $parsedRealCharger
   * @return void
   */
  private function updateConnectorsIfChanged( $parsedRealCharger ): void
  {
    $localCharger = $this -> data -> getLocalCharger( $parsedRealCharger[ 'charger_id' ] );
    
    if( ShouldUpdateChecker :: checkConnectors( $localCharger, $parsedRealCharger ) )
    {
      $this -> updateChargerConnectors( $localCharger, $parsedRealCharger[ 'connectors' ] );
    }
  }

  /**
   * Update charger connectors.
   * 
   * @param App\Charger $db_charger
   * @param array $connectors
   * @return void
   */
  private function updateChargerConnectors( $localCharger, $connectors )
  {
    $existingConnTypeIds = $localCharger
            -> connector_types
            -> pluck('id')
            -> all();

    $localCharger 
      -> connector_types()
      -> updateExistingPivot($existingConnTypeIds, ['status' => 'inactive']);
    
    $this -> addConnectors( $localCharger, $connectors );
  }

  /**
   * Check if this connector exits, 
   * and if not add into the database
   * 
   * @param string $connector
   * @return void
   */
  private function addConnectorIfNotAdded( $connector )
  {
    $connectorsTypes = $this -> data -> getLocalConnectorTypes() -> pluck( 'name' ) -> all();
    
    if( ! in_array( $connector, $connectorsTypes ) ){
      ConnectorType :: create([ 'name' => $connector ]);
      $this -> data -> setLocalConnectorTypes( ConnectorType :: all() );
    }
  }

 /**
  * Insert new charger with connectors
  * 
  * @param  array $charger
  * @return void
  */
  private function insertNewChargerWithConnectors( $parsedRealCharger )
  {
    $connectors = $parsedRealCharger[ 'connectors' ];
    unset( $parsedRealCharger[ 'connectors' ] );
    $newCharger = Charger :: create( $parsedRealCharger );
    $this -> addConnectors( $newCharger, $connectors );
  }

  /**
   * Add new connectors to charger.
   * 
   * @param  $charger
   * @param  $connectors
   * @return void
   */
  private function addConnectors( $localCharger, $connectors )
  {
    foreach($connectors as $connector)
    {  
      $this -> addConnectorIfNotAdded($connector -> type);
      
      $localCharger
        -> connector_types()
        -> attach(
          $this -> data -> getLocalConnectorTypeId( $connector -> type ),
          [
            'm_connector_type_id' => $connector -> id,
          ]
        );
    }
  }
}