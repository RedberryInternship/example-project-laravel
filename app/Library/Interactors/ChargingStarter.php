<?php

namespace App\Library\Interactors;

use App\Library\RequestModels\ChargingStarter as ChargingStarterRequest;

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
   * Order for charging process.
   * 
   * @var 
   */
  private $order;

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
  function __construct( ChargingStarterRequest $requestModel )
  {
    $this -> requestModel         = $requestModel;
    $this -> chargerConnectorType = ChargerConnectorType :: with( 'charger' ) 
          -> find( $this -> requestModel -> getChargerConnectorTypeId());
  }

  /**
   * Start charging process.
   * 
   * @return void
   */
  public function start(): void
  {
    $this -> createOrder();

    $result = $this -> startChargingProcess();
    
    OrderEditor :: update(
      $this -> order,
      $result,
      $this -> chargerConnectorType,
    );
    
    FastChargerPayer :: pay( 
      $this -> order, 
      $this -> requestModel -> isChargingTypeByAmount() 
    );

    KilowattRecordCreator :: create( $this -> order );
  }

  /**
   * Create order for charging process.
   * 
   * @return void
   */
  private function createOrder(): void
  {
    $this -> order = OrderCreator :: create( $this -> requestModel );
  }

  /**
   * Start charging process.
   */
  public function startChargingProcess()
  {
    return ChargingProcessStarter :: start(
      $this -> chargerConnectorType -> charger -> charger_id,
      $this -> chargerConnectorType -> m_connector_type_id,
    );
  }
  
  /**
   * Get order.
   * 
   * @return Order
   */
  public function getOrder()
  {
    return $this -> order;
  }
}