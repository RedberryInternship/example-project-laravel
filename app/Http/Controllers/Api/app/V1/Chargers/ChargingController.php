<?php

namespace App\Http\Controllers\Api\App\V1\Chargers;

use App\Http\Controllers\Controller;

use App\ChargerTransaction;
use App\ChargerConnectorType;

use App\Http\Requests\StartCharging;
use App\Http\Requests\StopCharging;

use App\Facades\Charger;

class ChargingController extends Controller
{
  /**
   * Status code for response.
   * 
   * @var int
   */
  private $status_code;

  /**
   * Message for response.
   * 
   * @var string
   */
  private $message;

  /**
   * Constructor for initializing response parameters.
   * 
   * @return void
   */
  public function __construct()
  {
    $this -> status_code = 200;
    $this -> message = '';
  }

  /**
   * Route method for starting Charging
   * 
   * @param App\Http\Requests\StartCharging $request
   * @return Illuminate\Http\JsonResponse
   */
  public function start(StartCharging $request)
  { 

    $charger_connector_type_id = $request -> get('charger_connector_type_id');
    $charger_connector_type = ChargerConnectorType::find($charger_connector_type_id);

    $charger = $charger_connector_type -> charger;
    
    if( ! Charger::isChargerFree( $charger -> charger_id )){
      $this -> message = 'The Charger is not free.';
      return $this -> respond();
    }
    
    $transactionID = $this -> startCharging(
      $charger -> charger_id, 
      $charger_connector_type -> m_connector_type_id
    );
    
    if(!$transactionID){
      return $this -> respond();
    }

    $charger_transaction = ChargerTransaction::create([
      'charger_id' => $charger -> id,
      'connector_type_id' => $charger_connector_type -> connector_type_id,
      'm_connector_type_id' => $charger_connector_type -> m_connector_type_id,
      'transactionID' => $transactionID,
    ]);

    $transaction_info = $this -> getTransactionInfo($transactionID);
    $charger_transaction -> createKilowatt($transaction_info -> consumed);

    $this -> message = 'Charging successfully started!';
    return $this -> respond();
  }

  /**
   * private method for starting charging with Misha's back
   * and parsing responses to appropriate messages.
   * 
   * @param int $charger_id
   * @param int $connector_id
   * @return false|string
   */
  private function startCharging($charger_id, $connector_id){
    $result = Charger::start($charger_id, $connector_id);
    
    if($result['status_code'] == 700){
      switch($result['data'] -> status){
        case -2:
          $this -> status_code = 400;
          $this -> message = 'No such charger with charger_id of '. $charger_id;
          return false;

        case -100:
          $this -> status_code = 400;
          $this -> message = 'Charger with charger_id of '.$charger_id.' is already charging!';
        return false;

        case 0:
          $transactionID = $result['data'] -> data;
          return $transactionID;
      }
    }
    else
    {
      $this -> status_code = 707;
      $this -> message = 'Misha\'s Error';
      return false;
    }
  }

  /**
   * Get transaction info from Misha's back.
   * 
   * @param string $transactionID
   * @return object
   */
  private function getTransactionInfo($transactionID)
  {
    $info = Charger::transactionInfo($transactionID);
    return $info['data'] -> data;
  }


  /**
   * Route method for stop charging call to Misha's back.
   * 
   * @param App\Http\Requests\StopCharging $request
   * @return Illuminate\Http\JsonResponse
   */
  public function stop(StopCharging $request)
  {
    $charger_connector_type_id = $request -> get('charger_connector_type_id');
    $charger_connector_type = ChargerConnectorType::find($charger_connector_type_id);
    
    $charger = $charger_connector_type -> charger;
    $charger_transaction = $charger_connector_type -> charger_transaction_first();
    $transactionID = $charger_transaction -> transactionID;
   
    $has_charging_stopped = $this -> sendStopChargingRequestToMisha($charger -> charger_id, $transactionID);
    
    if($has_charging_stopped){
      $charger_transaction -> status = 'CHARGED';
      $charger_transaction -> save();

      $this -> message = "Charging successfully stopped!";
   }
   else{
     $this -> message = "Something Went Wrong!";
   }

   return $this -> respond();
  }

  /**
   * Send stop charging request to Misha's back
   * 
   * @param int $charger_id
   * @param string $transactionID
   * @return bool
   */
  public function sendStopChargingRequestToMisha($charger_id, $transactionID)
  {
    $result = Charger::stop($charger_id, $transactionID) ;
    return $result['data'] -> data == $transactionID;
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
        'status-code' => $this -> status_code,
        'message' => $this -> message,
      ], $this -> status_code);
  }

}