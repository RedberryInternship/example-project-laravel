<?php

namespace App\Library\Entities\ChargingProcess;

use App\Enums\OrderStatus as OrderStatusEnum;
use Carbon\Carbon;

trait Timestamp
{
  /**
   * Get stop charging timestamp.
   * 
   * @return Carbon|null
   */
  private function getStopChargingTimestamp()
  {
      if( $this -> getChargingStatusTimestamp( OrderStatusEnum :: USED_UP ) )
      {
          return $this -> getChargingStatusTimestamp( OrderStatusEnum :: USED_UP );
      }

      return $this -> getChargingStatusTimestamp( OrderStatusEnum :: CHARGED );
  }

  /**
   * Get penalty timestamp.
   * 
   * @return Carbon|string
   */
  private function getPenaltyTimestamp()
  {
      $penaltyTimestamp = $this -> getChargingStatusTimestamp( OrderStatusEnum :: ON_FINE );
      
      return $penaltyTimestamp;
  }

  /**
   * Get microtime in milliseconds.
   * 
   * @return float
   */
  public function getChargingStatusTimestampInMilliseconds( $status )
  {
      $timestamp      = $this -> charging_status_change_dates [ $status ];

      $milliseconds   = $timestamp * 1000;
      $milliseconds   = round( $milliseconds );

      return $milliseconds;
  }

  /**
   * Get charging status timestamp.
   * 
   * @param   string $status
   * @return  Carbon|null
   */
  public function getChargingStatusTimestamp( $status )
  {
      $statusTimestamp = $this -> charging_status_change_dates [ $status ];
            
      if( ! $statusTimestamp )
      {
                return null;
      }
            
      return Carbon :: createFromTimestamp( $statusTimestamp );
  }
}