<?php

namespace App\Http\Controllers\Api\App\V1\Chargers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Charger as ChargerModel;
use App\ChargerTransaction;

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


    /** 
     * get connector_type_id from request
     * and retrieve charger_connector_type record
     */
    $charger_connector_type_id = $request -> get('charger_connector_type_id');
    $charger_connector_type = DB::table('charger_connector_types')
      -> where('id', $charger_connector_type_id ) -> first();

    /**
     * get charger with 
     * charger_connector_type -> charger_id
     */
    $charger = ChargerModel::find($charger_connector_type -> charger_id);
    
    
    /**
     * start charging with misha's back, which if succeeds
     * will return TransactionID if not 
     * it's gonna give us false.
     */
    $transactionID = $this -> startCharging($charger -> charger_id, $charger_connector_type -> m_connector_type_id);
    
    /**
     * if charging couldn't be started
     * respond with appropriate response.
     */
    if(!$transactionID){
      return $this -> respond();
    }


    /**
     * if start charging went well,
     * we will create new charger_transaction record.
     */
    $charger_transaction = ChargerTransaction::create([
      'charger_id' => $charger -> id,
      'connector_type_id' => $charger_connector_type -> connector_type_id,
      'm_connector_type_id' => $charger_connector_type -> m_connector_type_id,
      'transactionID' => $transactionID,
    ]);

    /**
     * then we're gonna add current kilowatt value
     * to our transaction record. 
     */
    $charger_transaction -> createKilowatt(0);

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
    return $info = Charger::transactionInfo($transactionID);
  }


  /**
   * Route method for stop charging call to Misha's back.
   * 
   * @param App\Http\Requests\StopCharging $request
   * @return Illuminate\Http\JsonResponse
   */
  public function stop(StopCharging $request)
  {

    // Stop Charging With Misha's Charger
    
    return response() -> json("ok", $this -> status_code);
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