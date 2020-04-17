<?php

namespace App\Http\Controllers\Api\App\V1\Chargers;

use App\ChargerConnectorType;
use App\Http\Controllers\Controller;

class StatusController extends Controller{

  /**
   * Response status code.
   * 
   * @var int $status_code
   */
  private $status_code;

  /**
   * Response message.
   * 
   * @var string $message
   */
  private $message;

  /**
   * Payload parameter for response.
   * 
   * @var array $payload
   */
  private $payload;

  /**
   * Constructor for initiating response
   * parameters.
   * 
   * @return void
   */
   public function __construct()
   {
     $this -> status_code = 200;
     $this -> message     = '';
     $this -> payload     = [];
   }

  /**
   * Get charging status.
   * 
   * @param int $charger_connector_id
   */
  public function getChargingStatus( $charger_connector_type_id )
  {

    $charger_connector_type = ChargerConnectorType::find( $charger_connector_type_id );
    
    if( ! $charger_connector_type )
    {
      $this -> status_code = 204;
      $this -> message     = 'There is no charger transaction '
                              . 'with charger_connector_type_id of '
                              . $charger_connector_type_id .'.';
    }
    else
    {
      $charger_transaction =  $charger_connector_type -> charger_transaction_first();

      if( $charger_transaction ){
        $this -> payload[ 'status' ] = $charger_transaction -> status;
        $this -> message             = "Charging status successfully retrieved.";
      }
      else
      {
        $this -> status_code  = 204;
        $this -> message      = 'There is no charger transaction '
                                . 'with charger_connector_type_id of '
                                . $charger_connector_type_id .'.';
      }
    }
    return $this -> respond();
  }


  /**
   * Json response structure.
   * 
   * @return Illuminate\Http\JsonResponse
   */
  private function respond()
  {
    return response() -> json([
      'status-code' => $this -> status_code,
      'message'     => $this -> message,
      'payload'     => $this -> payload,
    ], $this -> status_code);
  }
}