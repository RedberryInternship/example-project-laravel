<?php

namespace App\Library\Interactors;

use App\Library\DataStructures\ChargingStarter as ChargingStarterRequest;

use App\Library\Entities\ChargingStart\ChargingProcessStarter;
use App\Library\Entities\ChargingStart\KilowattRecordCreator;
use App\Library\Entities\ChargingStart\FastChargerPayer;
use App\Library\Entities\ChargingStart\OrderCreator;
use App\Library\Entities\ChargingStart\OrderEditor;

use App\ChargerConnectorType;
use App\Order;

class ChargingStarter
{
  /**
   * Request model for starting charging process.
   * 
   * @var ChargingStarterRequest $requestModel
   */
  private $requestModel;

  /**
   * Charger connector type.
   * 
   * @var ChargerConnectorType $chargerConnectorType
   */
  private $chargerConnectorType;

  /**
   * Constructor for initializing request model.
   * 
   * @param ChargingStarterRequest $requestModel
   */
  public static function prepare( ChargingStarterRequest $requestModel )
  {
    $chargerConnectorTypeId = $requestModel -> getChargerConnectorTypeId();

    $instance = new self;
    $instance -> requestModel = $requestModel;
    $instance -> chargerConnectorType = ChargerConnectorType :: with( 'charger' ) -> find( $chargerConnectorTypeId );
    
    return $instance;
  }
  
  /**
   * Start charging process.
   * 
   * @return void
   */
  public function start(): Order
  {
    $realChargerConnectorId = $this -> chargerConnectorType -> m_connector_type_id;
    $isChargingByAmount     = $this -> requestModel -> isChargingTypeByAmount();
    $realChargerId          = $this -> chargerConnectorType -> charger -> charger_id;
    $isChargerFast          = $this -> chargerConnectorType -> isChargerFast();
    $order                  = OrderCreator :: create( $this -> requestModel );

    $result = ChargingProcessStarter :: instance()
      -> setChargerId       ( $realChargerId )
      -> setConnectorTypeId ( $realChargerConnectorId  )
      -> execute();

    OrderEditor :: instance()
      -> setStartChargingResult( $result        )
      -> setIsChargerFast      ( $isChargerFast )
      -> setOrder              ( $order         )
      -> update();
    
    if( $result -> didSucceeded() )
    {
      FastChargerPayer :: instance()
        -> setIsChargerFast ( $isChargerFast      )
        -> setIsByAmount    ( $isChargingByAmount )
        -> setOrder         ( $order              )
        -> pay();
    }

    KilowattRecordCreator :: create( $order );

    return $order;
  }
}