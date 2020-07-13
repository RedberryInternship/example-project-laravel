<?php

namespace App\Library\Interactors;

use App\Library\Entities\Payments\Refunder;
use App\Library\Entities\Payments\Cutter;
use App\Library\Entities\Payments\Finer;

use App\Order;

class Payment
{
  /**
   * Make refunding transaction.
   * 
   * @param  Order $order
   * @param  int   $amount
   * @return void
   */
  public static function refund( Order $order, int $amount ): void
  {
    Refunder :: refund( $order, $amount );
  }

  /**
   * Make usual cutting transaction.
   * 
   * @param  Order $order
   * @param  int   $amount
   * @return void
   */
  public static function cut( Order $order, int $amount ): void
  {
    Cutter :: cut( $order, $amount );
  }

  /**
   * Make fine/penalty transaction.
   * 
   * @param  Order $order
   * @param  int   $amount
   * @return void
   */
  public static function charge( Order $order, int $amount ): void
  {
    Finer :: charge( $order, $amount );
  }
}