<?php

namespace App\Library\RequestModels;

use App\Enums\ChargingType as ChargingTypeEnum;

class ChargingStarter
{
  private $chargerConnectorTypeId;
  private $chargingType;
  private $price;
  private $userCardId;

  /**
   * Set charger connector type id.
   * 
   * @param  int $id
   * @return void
   */
  function setChargerConnectorTypeId( $id )
  {
    $this -> chargerConnectorTypeId = $id;
  }

  /**
   * Get charger connector type id.
   * 
   * @return int
   */
  function getChargerConnectorTypeId()
  {
    return $this -> chargerConnectorTypeId;
  }

  /**
   * Set charging type.
   * 
   * @param string
   * @return void
   */
  function setChargingType( $type )
  {
    $this -> chargingType = $type;
  }

  /**
   * Get charging type.
   * 
   * @return void
   */
  function getChargingType()
  {
    return $this -> chargingType;
  }

  /**
   * Set charging price.
   * 
   * @param  int|float|string $amount
   * @return void 
   */
  function setPrice( $amount )
  {
    $this -> price = $amount;
  }

  /**
   * Get charging price.
   * 
   * @return int|float|string
   */
  function getPrice()
  {
    return $this -> price;
  }

  /**
   * Set user card id.
   * 
   * @param  int $id
   * @return void
   */
  function setUserCardId( $id )
  {
    $this -> userCardId = $id;
  }

  /**
   * Get user card id.
   * 
   * @return void
   */
  function getUserCardId()
  {
    return $this -> userCardId;
  }

  /**
   * Determine if charging type is BY_AMOUNT.
   * 
   * @return bool
   */
  function isChargingTypeByAmount()
  {
      return $this -> chargingType == ChargingTypeEnum :: BY_AMOUNT;
  }
}