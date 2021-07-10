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

    LaravelLogger :: channel( 'kilowatt-records' ) -> info( "KILOWATT CREATED" );
    LaravelLogger :: channel( 'kilowatt-records' ) -> info( "Transaction Id: $transactionId" );
    LaravelLogger :: channel( 'kilowatt-records' ) -> info( "Kilowatt Created: $value" );
    LaravelLogger :: channel( 'kilowatt-records' ) 
      -> info( "-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -" );
  }

  /**
   * Update kilowatt record.
   */
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
    $now = now();
    LaravelLogger :: channel( 'kilowatt-records' ) -> info( "KILOWATT UPDATED" );
    LaravelLogger :: channel( 'kilowatt-records' ) -> info( "Transaction ID: $transactionId" );
    LaravelLogger :: channel( 'kilowatt-records' ) -> info( "Current Watts: $watts" );
    LaravelLogger :: channel( 'kilowatt-records' ) -> info( "Previous Kilowatt: $previousKilowattValue" );
    LaravelLogger :: channel( 'kilowatt-records' ) -> info( "Kilowatt Difference: $kilowattValueDifference" );
    LaravelLogger :: channel( 'kilowatt-records' ) -> info( "Previously Updated: $previousUpdateDatetime" );
    LaravelLogger :: channel( 'kilowatt-records' ) -> info( "Now: $now" );
    LaravelLogger :: channel( 'kilowatt-records' ) -> info( "Diff in Secs: $diffInSeconds" );
    LaravelLogger :: channel( 'kilowatt-records' ) -> info( "Diff in Hrs: $diffInHours" );
    LaravelLogger :: channel( 'kilowatt-records' ) -> info( "Current Charging Power: $currentChargingPower" );
    
    LaravelLogger :: channel( 'kilowatt-records' ) 
      -> info( "-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -" );
  }

  /**
   * Warrning, charger doesn't have right charging price.
   */
  public static function noChargingPrice($chargerCode, $connectorId, $charingPower): void
  {
    LaravelLogger :: channel( 'charging-power-explosions' ) 
      -> warning(
        "Charger #$chargerCode with connectorID: $connectorId - doesn't have charging price with charging power: $charingPower"
      );
  }
}