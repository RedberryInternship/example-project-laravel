<?php

namespace App\Library\Entities\ChargingFinish;

use Illuminate\Support\Facades\Log;
use App\Order;

class OrderGetter
{
  /**
   * Get order to finish by the transactionId.
   * 
   * @param int $transactionId
   * @return Order|null
   */
  public static function get( $transactionId ): ?Order
  {
    $order = Order :: where( 'charger_transaction_id', $transactionId ) -> first();

    ! $order && Log :: channel( 'feedback-finish' ) -> info( 'Nothing To Finish - Transaction ID - ' . $transactionId );

    return $order;
  }
}