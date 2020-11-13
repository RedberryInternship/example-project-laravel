<?php

namespace App\Library\Entities\ChargingUpdate;

use App\Order;

class OrderGetter
{
  /**
   * Get order for updating live charging process.
   * 
   * @param $transactionId
   * @return Order 
   */
  public static function get( $transactionId ): ?Order
  {
    return Order :: with( 'kilowatt' )
      -> where( 'charger_transaction_id', $transactionId )
      -> first();
  }
}