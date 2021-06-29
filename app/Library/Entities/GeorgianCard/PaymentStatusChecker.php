<?php

namespace App\Library\Entities\GeorgianCard;

use App\Enums\OrderStatus as OrderStatusEnum;

class PaymentStatusChecker
{
  /**
   * Determine if payment is successful.
   * 
   * @return bool
   */
  public static function succeeded(): bool
  {
   return request() -> get( 'result_code'  ) == 1;
  }

  /**
   * Return payment failure status.
   * 
   * @return string
   */
  public static function getFailureStatus()
  {
    if( ! self :: succeeded() )
    {
      return OrderStatusEnum :: PAYMENT_FAILED;
    }
  }
}