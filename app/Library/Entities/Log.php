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

  /**
   * Successfully finished order.
   * 
   * @param int $transactionId
   * @return void
   */
  public static function orderSuccessfullyFinished( $transactionId ): void
  {
    LaravelLogger :: channel( 'feedback-finish' ) -> info( 'FINISHED - Transaction ID - ' . $transactionId );
  }


  /**
   * Kilowatt record created.
   */
  public static function kilowattCreated( $transactionId, $value ): void
  {
    LaravelLogger :: channel( 'kilowatt-records' ) -> info( "transaction id: $transactionId" );
    LaravelLogger :: channel( 'kilowatt-records' ) -> info( "kilowatt created: $value" );
    LaravelLogger :: channel( 'kilowatt-records' ) 
      -> info( "-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -" );
  }

  public static function kilowatUpdate(
    $transactionId,
    $watts,
    $previousKilowattValue,
    $kilowattValueDifference,
    $previousUpdateDatetime,
    $diffInSeconds,
    $diffInHours,
    $currentChargingPower
  )
  {
    LaravelLogger :: channel( 'kilowatt-records' ) -> info( "transaction id: $transactionId" );

    LaravelLogger :: channel( 'kilowatt-records' ) 
      -> info( "current watts: $watts | previous kilowatt: $previousKilowattValue | kilowatt difference: $kilowattValueDifference" );

    $now = now();
    LaravelLogger :: channel( 'kilowatt-records' ) 
      -> info( "previously updated: $previousUpdateDatetime | now: $now | diff in secs: $diffInSeconds | diff in hrs: $diffInHours" );
    
    LaravelLogger :: channel( 'kilowatt-records' ) 
      -> info( "current charging power: $currentChargingPower" );
    
    LaravelLogger :: channel( 'kilowatt-records' ) 
      -> info( "-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -" );
  }
}