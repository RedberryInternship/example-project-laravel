<?php

namespace App\Library\Entities\GeorgianCard;

use App\Enums\OrderStatus as OrderStatusEnum;
use App\Library\Interactors\Firebase;
use App\Library\Entities\Helper;
use App\Facades\Simulator;
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
    /**
     * @var Order
     */
    $order = Order :: with( 'charger_connector_type.charger' ) -> find( request() -> get( 'o_id' ) );

    if( $order )
    {
      $order->stampPaymentFailure();
      $order->stampLastChargingPowerRecord();
      self :: stopCharging    ( $order );
      self :: updateOrder     ( $order );
      self :: sendNotification( $order );
    }
  }

  /**
   * Update order.
   * 
   * @return void
   */
  private static function updateOrder( $order )
  {
    $order -> charger_connector_type -> isChargerFast()
      ? $order -> updateChargingStatus( OrderStatusEnum :: FINISHED )
      : $order -> updateChargingStatus( OrderStatusEnum :: CHARGED  );
  }

  /**
   * Send stop request to real chargers.
   * 
   * @return void
   */
  private static function stopCharging( $order )
  {
    $chargerId     = $order -> getCharger() -> charger_id;
    $transactionId = $order -> charger_transaction_id;

    Charger :: stop( $chargerId, $transactionId );
    
    if( Helper :: isDev() && $order -> charger_connector_type -> isChargerFast() )
    {
      Simulator :: plugOffCable( $chargerId );
    }
  }

  /**
   * Send firebase notification.
   * 
   * @param  int $chargerTransactionId
   * @return void
   */
  public static function sendNotification( $order )
  {
    Firebase :: sendPaymentFailedNotificationWithData( $order -> charger_transaction_id );
  }
}