<?php

namespace App\Http\Controllers\Api\app\V1;

use App\Http\Controllers\Controller;

use App\Enums\ChargingType as ChargingTypeEnum;
use App\Enums\OrderStatus as OrderStatusEnum;
use App\Enums\ChargerType as ChargerTypeEnum;

use App\Traits\Message;

use App\ChargerConnectorType;
use App\Order;
use App\User;

use App\Http\Requests\StartCharging;
use App\Http\Requests\StopCharging;

use App\Http\Resources\Order as OrderResource;

use App\Facades\Charger;

class ChargingController extends Controller
{
  use Message;

  /**
   * Status code for response.
   * 
   * @var int
   */
  private   $status_code;

  /**
   * Status message for concrete description.
   */
  private   $status;

  /**
   * Message for app notifications.
   * 
   * @var string
   */
  private   $message;

  /**
   * Constructor for initializing response parameters.
   * 
   * @return void
   */
  public function __construct()
  {
    $this -> status_code = 200;
    $this -> status      = '';
    $this -> message     = '';
  }

  /**
   * Route method for starting Charging
   * 
   * @param App\Http\Requests\StartCharging $request
   * @return Illuminate\Http\JsonResponse
   */
  public function start(StartCharging $request)
  { 
    $chargerConnectorTypeId   = $request -> get( 'charger_connector_type_id' );
    $chargerConnectorType     = ChargerConnectorType::find( $chargerConnectorTypeId );
    $chargerType              = $chargerConnectorType -> determineChargerType();

    return $chargerType == ChargerTypeEnum :: FAST
      ? $this -> startFastCharging()
      : $this -> startLvl2Charging();
  }

  /**
   * Start charging on fast charger.
   * 
   * @return  Illuminate\Http\JsonResponse
   */
  private function startFastCharging()
  {
    $chargerConnectorTypeId   = request() -> get( 'charger_connector_type_id' );
    $chargingType             = request() -> get( 'charging_type' );
    $chargerConnectorType     = ChargerConnectorType::find( $chargerConnectorTypeId );
    
  }

  /**
   * Start charging on Lvl 2 charger.
   * 
   * @return  Illuminate\Http\JsonResponse
   */
  private function startLvl2Charging()
  {
    $chargerConnectorTypeId   = request() -> get( 'charger_connector_type_id' );
    $userCardId               = request() -> get( 'user_card_id' );
    $chargerConnectorType     = ChargerConnectorType :: find( $chargerConnectorTypeId );
    $chargingType             = request() -> get( 'charging_type' );
    $isByAmount               = $chargingType == ChargingTypeEnum :: BY_AMOUNT;
    $targetPrice              = $isByAmount ? request() -> get( 'price' ) : null;

    $transactionID = Charger::start(
      $chargerConnectorType   -> charger -> charger_id, 
      $chargerConnectorType   -> m_connector_type_id
    );

    $order = Order::create([
      'charger_connector_type_id' => $chargerConnectorType -> id,
      'charger_transaction_id'    => $transactionID,
      'charging_status'           => OrderStatusEnum :: INITIATED,
      'user_card_id'              => $userCardId,
      'user_id'                   => auth() -> user() -> id,
      'charging_type'             => $chargingType,
      'target_price'              => $targetPrice,
    ]);

    $transaction_info = Charger::transactionInfo( $transactionID );
    $order -> createKilowatt( $transaction_info -> consumed );

    $order -> load( 'charger_connector_type.charger'        );
    $order -> load( 'charger_connector_type.connector_type' );

    return new OrderResource( $order );
  }


  /**
   * Route method for stop charging call to Misha's back.
   * 
   * @param App\Http\Requests\StopCharging $request
   * @return Illuminate\Http\JsonResponse
   */
  public function stop()
  {
    $orderId        = request() -> get( 'order_id' );

    if( ! $orderId )
    {
      throw new \Exception( 'Gimme order_id man!' );
    }

    $order          = Order :: with(
      [
        'charger_connector_type.charger',
        'charger_connector_type.connector_type',
        'user',
      ]
    ) -> find( $orderId );

    $charger        = $order -> charger_connector_type -> charger;
    $transactionID  = $order -> charger_transaction_id;
   
    $this -> sendStopChargingRequestToMisha( $charger -> charger_id, $transactionID );
    
    $order -> charging_status = OrderStatusEnum :: CHARGED;
    $order -> save();


    $resource = new OrderResource( $order );
    $resource -> setAdditionalData(
      [
        'message' => $this -> messages [ 'charging_successfully_finished' ],
      ]
    );
    
    return $resource;
  }

  /**
   * Send stop charging request to Misha's back
   * 
   * @param int $charger_id
   * @param string $transactionID
   * @return bool
   */
  public function sendStopChargingRequestToMisha( $charger_id, $transactionID )
  {
    return Charger::stop( $charger_id, $transactionID );
  }

  /**
   * Create appropriate response 
   * from status code and message.
   * 
   * @return Illuminate\Http\JsonResponse
   */
  private function respond()
  {
    return response() 
      -> json([
        'status_code' => $this -> status_code,
        'status'      => $this -> status,
        'message'     => (object) $this -> message,
      ], $this -> status_code);
  }

}