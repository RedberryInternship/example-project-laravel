<?php

namespace App\Library\Entities\ChargingFinish;

use App\Enums\OrderStatus as OrderStatusEnum;
use App\Library\Interactors\Firebase;
use App\Order;

class FinishAndNotify
{
  /**
   * Change charging status to FINISHED if 
   * possible and notify user.
   * 
   * @param Order $order
   * @return void
   */
  public static function execute( Order &$order ): void
  {
    if( $order -> canGoToFinishStatus() )
      {
          $order -> updateChargingStatus( OrderStatusEnum :: FINISHED );
          Firebase :: sendFinishNotificationWithData( $order -> charger_transaction_id );
      }
  }
}