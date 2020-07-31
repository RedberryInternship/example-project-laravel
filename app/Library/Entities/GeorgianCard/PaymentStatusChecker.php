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
  public static function getFailureStatus(): string
  {
    if( ! self :: succeeded() )
    {
      switch( self :: getFailureCode() )
      {
        case -2:
          return OrderStatusEnum :: BANKRUPT;
          
        default:
          return OrderStatusEnum :: PAYMENT_FAILED;
      }
    }
  }

  /**
   * Get failure status code.
   * 
   * @return int
   */
  private static function getFailureCode()
  {
    return request() -> get( 'ext_result_code' );
  }
}