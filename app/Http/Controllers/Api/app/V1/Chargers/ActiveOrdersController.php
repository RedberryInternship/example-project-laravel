<?php

namespace App\Http\Controllers\Api\App\V1\Chargers;

use App\Http\Controllers\Controller;

use App\Http\Resources\ActiveOrders;

use App\ChargerConnectorType;
use App\User;
use Illuminate\Http\Resources\Json\Resource;

class ActiveOrdersController extends Controller{

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

     Resource :: withoutWrapping();
   }

  /**
  * Return active orders.
  *
  * @return Illuminate\Http\JsonResponse
  */
  public function get()
  {
    $userId = auth() -> user() -> id;
    $user   = User :: with([
      'active_orders.charger_connector_type.charger',
      'active_orders.charger_connector_type.connector_type',
    ]) -> find( $userId );

    
    return ActiveOrders :: collection( $user -> active_orders );
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
      $this -> status_code = 404;
      $this -> message     = 'There is no charger transaction '
                              . 'with charger_connector_type_id of '
                              . $charger_connector_type_id .'.';
    }
    else
    {
      $order =  $charger_connector_type -> orders -> first();

      if( $order ){
        $this -> payload[ 'status' ] = $order -> charging_status;
        $this -> message             = "Charging status successfully retrieved.";
      }
      else
      {
        $this -> status_code  = 404;
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