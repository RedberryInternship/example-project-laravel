<?php

namespace App\Http\Controllers\Api\App\V1\Chargers;

use App\Http\Controllers\Controller;

use App\Enums\ChargingType;
use App\Enums\OrderStatus;

use App\Traits\Message;

use App\Order;
use App\ChargerConnectorType;

use App\Http\Requests\StartCharging;
use App\Http\Requests\StopCharging;

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
    $chargingType             = $request -> get( 'charging_type' );
    $chargerConnectorType     = ChargerConnectorType::find( $chargerConnectorTypeId );
    $charger                  = $chargerConnectorType -> charger;
    
    // TODO: Tell Beqa CHARGING-TYPEs are changed as so [ BY_AMOUNT, FULL_CHARGE ]
    if( $chargingType == ChargingType :: BY_AMOUNT )
    {
      $price = $request -> get( 'price' );
    }

    if( ! Charger::isChargerFree( $charger -> charger_id ))
    {
      $this -> message      = $this -> messages [ 'charger_is_not_free' ];
      $this -> status       = 'Charger is not free.';
      $this -> status_code  = 400;
      return $this -> respond();
    }
    
    $transactionID = Charger::start(
      $charger                -> charger_id, 
      $chargerConnectorType   -> m_connector_type_id
    );

    $order = Order::create([
      'charger_connector_type_id' => $chargerConnectorTypeId,
      'charger_transaction_id'    => $transactionID,
      'charging_status'           => OrderStatus :: INITIATED,
    ]);

    $transaction_info = Charger::transactionInfo( $transactionID );
    $order -> createKilowatt( $transaction_info -> consumed );

    $this -> message = $this -> messages[ 'charging_successfully_started' ];
    $this -> status  = 'Charging Successfully started!';
    return $this -> respond();
  }


  /**
   * Route method for stop charging call to Misha's back.
   * 
   * @param App\Http\Requests\StopCharging $request
   * @return Illuminate\Http\JsonResponse
   */
  public function stop( StopCharging $request )
  {
    $charger_connector_type_id  = $request -> get( 'charger_connector_type_id' );
    $charger_connector_type     = ChargerConnectorType :: with('orders') -> find( $charger_connector_type_id );
    
    $charger                    = $charger_connector_type -> charger;
    $order                      = $charger_connector_type -> orders -> first();
    $transactionID              = $order -> charger_transaction_id;
   
    $this -> sendStopChargingRequestToMisha( $charger -> charger_id, $transactionID );
  
    $order -> charging_status = OrderStatus :: CHARGED;
    $order -> save();

    $this -> message = $this -> messages [ 'charging_successfully_finished' ];
    $this -> status  = 'Charging successfully finished!';
   
   return $this -> respond();
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