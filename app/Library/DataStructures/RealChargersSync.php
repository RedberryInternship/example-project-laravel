<?php

namespace App\Library\DataStructures;

use App\ConnectorType;
use App\Charger;

class RealChargersSync
{
  /**
   * Build object with prerequisites.
   * 
   * @return self
   */
  public static function build(): self
  {
    $localChargers        = Charger :: with( 'connector_types' ) -> get();
    $localConnectorTypes  = ConnectorType :: all();
    
    return (new self) 
      -> setLocalChargers      ( $localChargers       )
      -> setLocalConnectorTypes( $localConnectorTypes );
  }

  /**
   * Real chargers data.
   * 
   * @var array<object> $realChargers
   */
  private $realChargers;

  /**
   * Local chargers data.
   * 
   * @var $localChargers
   */
   private $localChargers;

  /**
  * Local connector types.
  * 
  * @var $localConnectorTypes
  */
  private $localConnectorTypes;

  /**
   * Set real chargers.
   * 
   * @param  array
   * @return self
   */
  function setRealChargers( $realChargers ): self
  {
    $this -> realChargers = $realChargers;
    return $this;
  }

  /**
   * Get real chargers.
   * 
   * @return array
   */
  function getRealChargers()
  {
    return $this -> realChargers;
  }

  /**
   * Set local chargers.
   * 
   * @param  $localChargers
   * @return self
   */
  function setLocalChargers( $localChargers ): self
  {
    $this -> localChargers = $localChargers;
    return $this;
  }

  /**
   * Get local chargers.
   * 
   * @return $localChargers
   */
  function getLocalChargers()
  {
    return $this -> localChargers;
  }

  /**
   * Get local charger.
   * 
   * @param  int  $id
   * @return App\Charger
   */
  function getLocalCharger( $id )
  {
    return $this -> localChargers -> where( 'charger_id', $id ) -> first();
  }
  
  /**
   * Determine if specific local charger exists.
   * 
   * @param  int  $id
   * @return App\Charger
   */
  function localChargerExists( $id )
  {
    return !! $this -> getLocalCharger( $id );
  }

  /**
   * Set local connector types.
   * 
   * @param  $connectorTypes
   * @return self
   */
  function setLocalConnectorTypes( $localConnectorTypes ): self
  {
    $this -> localConnectorTypes = $localConnectorTypes;
    return $this;
  }

  /**
   * Get local connector type id by type name.
   * 
   * @param  string $localConnectorTypeName
   * @return int|null
   */
  function getLocalConnectorTypeId( string $localConnectorTypeName )
  {
    return $this -> localConnectorTypes -> where( 'name', $localConnectorTypeName ) -> first() -> id;
  }

  /**
   * Get local connector types.
   * 
   * @return $localConnectorTypes
   */
  function getLocalConnectorTypes()
  {
    return $this -> localConnectorTypes;
  }
}