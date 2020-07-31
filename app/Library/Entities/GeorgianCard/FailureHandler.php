<?php

namespace App\Library\Entities\GeorgianCard;

use App\Library\Entities\GeorgianCard\PaymentStatusChecker;
use App\Facades\Charger;
use App\Order;

class FailureHandler
{
  /**
   * handle failure.
   * 
   * @return void
   */
  public static function handle()
  {
    $order = Order :: with( 'charger_connector_type.charger' ) -> find( request() -> get( 'o_id' ) );

    if( $order )
    {
      self :: updateOrder ( $order );
      self :: stopCharging( $order );
    }
  }

  /**
   * Update order.
   * 
   * @return void
   */
  private static function updateOrder( $order )
  {
    $status = PaymentStatusChecker :: getFailureStatus();
    $order -> updateChargingStatus( $status );
  }

  /**
   * Send stop request to real chargers.
   * 
   * @return void
   */
  private static function stopCharging( $order )
  {
    $chargerId     = $order -> charger_connector_type -> charger -> charger_id;
    $transactionId = $order -> transaction_id;

    Charger :: stop( $chargerId, $transactionId );
  }
}