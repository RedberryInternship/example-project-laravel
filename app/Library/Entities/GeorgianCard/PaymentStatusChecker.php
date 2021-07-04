<?php

namespace App\Library\Entities\GeorgianCard;

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
}