<?php

namespace App\Library\Entities;

use Illuminate\Support\Facades\Log as LaravelLogger;

/**
 * Custom logging helper class.
 */
class Log
{
  /**
   * Order not found to update.
   * 
   * @param int $transactionId
   * @return void
   */
  public static function noOrderToUpdate( $transactionId ): void
  {
    LaravelLogger :: channel( 'feedback-update' ) -> info( 'Nothing To Update |'. $transactionId );
  }

  /**
   * Successfully updated order.
   * 
   * @param int $transactionId
   * @param int $value
   * @return void
   */
  public static function orderSuccessfullyUpdated( $transactionId, $value ): void
  {
    LaravelLogger :: channel( 'feedback-update' ) -> info( 'Update Happened | Transaction ID - ' . $transactionId . ' | Value - ' . $value );
  }
}