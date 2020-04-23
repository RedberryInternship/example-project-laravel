<?php

namespace App\Http\Controllers\Api\App\V1\Chargers;

use App\Http\Controllers\Controller;

use App\Traits\Message;

use App\ChargerTransaction;
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
    $charger_connector_type_id = $request -> get( 'charger_connector_type_id' );
    $charging_type             = $request -> get( 'charging_type' );
    $charger_connector_type    = ChargerConnectorType::find( $charger_connector_type_id );
    $charger                   = $charger_connector_type -> charger;
    
    if( $charging_type == 'BY-AMOUNT' )
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
      $charger_connector_type -> m_connector_type_id
    );

    $charger_transaction = ChargerTransaction::create([
      'charger_id'          => $charger -> id,
      'connector_type_id'   => $charger_connector_type -> connector_type_id,
      'm_connector_type_id' => $charger_connector_type -> m_connector_type_id,
      'transactionID'       => $transactionID,
    ]);

    $transaction_info = Charger::transactionInfo( $transactionID );
    $charger_transaction -> createKilowatt( $transaction_info -> consumed );

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
    $charger_connector_type     = ChargerConnectorType::find( $charger_connector_type_id );
    
    $charger                    = $charger_connector_type -> charger;
    $charger_transaction        = $charger_connector_type -> charger_transaction_first();
    $transactionID              = $charger_transaction -> transactionID;
   
    $this -> sendStopChargingRequestToMisha( $charger -> charger_id, $transactionID );
  
    $charger_transaction -> status = 'CHARGED';
    $charger_transaction -> save();

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