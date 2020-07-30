<?php

namespace App\Library\DataStructures;

class RealChargerAttributes
{
  /**
   * Real charger id.
   * 
   * @var int $chargerId
   */
  private $chargerId;

  /**
   * Real charger connector type id.
   * 
   * @var int $chargerConnectorTypeId
   */
  private $chargerConnectorTypeId;

  /**
   * Get instance.
   * 
   * @return RealChargerAttributes
   */
  public static function instance(): RealChargerAttributes
  {
    return new self;
  }

  /**
   * Set charger id.
   * 
   * @param  int $chargerId
   * @return void
   */
  public function setChargerId( $chargerId ): self
  {
    $this -> chargerId = $chargerId;
    return $this;
  }

  /**
   * Set charger connector type id.
   * 
   * @param  int $chargerConnectorTypeId
   * @return void
   */
  public function setChargerConnectorTypeId( $chargerConnectorTypeId ): self
  {
    $this -> chargerConnectorTypeId = $chargerConnectorTypeId;
    return $this;
  }

  /**
   * Get charger id.
   * 
   * @return int
   */
  public function getChargerId(): int
  {
    return $this -> chargerId;
  }

  /**
   * Get charger connector type id.
   * 
   * @return int
   */
  public function getChargerConnectorTypeId(): int
  {
    return $this -> chargerConnectorTypeId;
  }
}