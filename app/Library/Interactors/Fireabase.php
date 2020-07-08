<?php

namespace App\Library\Interactors;

use App\Library\Entities\Firebase\FinishNotificationSender;
use App\Library\Entities\Firebase\ActiveOrdersSender;

class Firebase
{
  /**
   * Send active orders to App.
   * 
   * @param  int $userId
   * @return void
   */
  public static function sendActiveOrders( $userId ): void
  {
    ActiveOrdersSender :: send( $userId );
  }

  /**
   * Send active orders with finished order 
   * at firing finish event.
   * 
   * @param  int $chargerTransactionId
   * @return void
   */
  public static function sendNotificationWithData( $chargerTransactionId ): void
  {
    FinishNotificationSender :: send( $chargerTransactionId );
  }
}