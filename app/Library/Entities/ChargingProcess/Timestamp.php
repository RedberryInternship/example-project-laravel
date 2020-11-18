<?php

namespace App\Library\Entities\ChargingProcess;

use App\Enums\OrderStatus as OrderStatusEnum;
use App\Library\Entities\Helper;
use Carbon\Carbon;
use App\Config;
use App\Order;

class Timestamp
{
  /**
   * Order instance for current charging process.
   * 
   * @var Order $order
   */
  private $order;

  /**
   * Set order.
   * 
   * @param Order $order
   */
  function __construct( Order &$order )
  {
    $this -> order = $order;  
  }

  /**
   * Build Timestamp instance.
   * 
   * @param   Order $order
   * @return  self
   */
  public static function build( Order &$order ): self
  {
    return new self( $order );
  }

  /**
   * Get initiated timestamp.
   * 
   * @return Carbon
   */
  public function getInitiatedTimestamp()
  {
    return $this -> getChargingStatusTimestamp( OrderStatusEnum :: INITIATED );
  }

  /**
   * Get start timestamp.
   * 
   * @return Carbon|null
   */
  public function getStartTimestamp(): ?Carbon
  {
    if(Helper :: isDev() || (! $this -> order -> charger_connector_type -> isChargerFast()))
    {
      return $this -> getChargingStatusTimestamp( OrderStatusEnum :: CHARGING );
    }
    else
    {
      return $this -> getOriginalStartTime();
    }
  }

  /**
   * Original start time.
   * 
   * @return \Carbon | null
   */
  public function getOriginalStartTime(): ?Carbon
  {
    $startDate = $this -> order -> real_start_date;

    return $startDate 
      ? Carbon :: createFromTimestamp( $startDate ) 
      : null;
  }

  /**
   * Original end time.
   * 
   * @return \Carbon | null
   */
  public function getOriginalEndTime(): ?Carbon
  {
    $endDate = $this -> order -> real_end_date;

    return $endDate 
      ? Carbon :: createFromTimestamp($endDate)
      : null; 
  }

  /**
   * Get end timestamp.
   * 
   * @return Carbon|null
   */
  public function getEndTimestamp(): ?Carbon
  {
    $isChargerFast   = $this -> order -> charger_connector_type -> isChargerFast();
    $endTimestamp    = null;

    if( $isChargerFast )
    {
        if( Helper :: isDev() )
        {
          $endTimestamp = $this -> getChargingStatusTimestamp( OrderStatusEnum :: FINISHED );
        }
        else
        {
          $endTimestamp = $this -> getOriginalEndTime();
        }
    }
    else
    {
        $endTimestamp = $this -> getStopChargingTimestamp();
    }
    
    if( ! $endTimestamp )
    {
        $endTimestamp = now();
    }
    
    return $endTimestamp;
  }

  /**
   * Get charging duration.
   * 
   * @return int
   */
  public function getChargingDuration(): ?int
  {
    $startTimestamp = $this -> getStartTimestamp();
    $endTimestamp   = $this -> getEndTimestamp();

    if( (!! $startTimestamp) && (!! $endTimestamp) )
    {
      return $startTimestamp -> diffInMinutes( $endTimestamp );
    }
    
    if( (!! $startTimestamp) && ! $endTimestamp )
    {
      return $startTimestamp -> diffInMinutes(now());
    }

    return 0;
  }

  /**
   * Get stop charging timestamp.
   * 
   * @return Carbon|null
   */
  public function getStopChargingTimestamp()
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
  public function getPenaltyTimestamp()
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
    $timestamp      = $this -> order -> charging_status_change_dates [ $status ];

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
    $statusTimestamp = @ $this -> order -> charging_status_change_dates [ $status ];
        
    if( ! $statusTimestamp )
    {
    return null;
    }
        
    return Carbon :: createFromTimestamp( $statusTimestamp );
  }

  /**
   * Calculate penalty start time.
   * 
   * @return milliseconds
   */
  public function calculatePenaltyStartTime()
  {
    $penaltyReliefModeStartTime = $this -> getStopChargingTimestamp();

    $config               = Config :: first();
    $penaltyReliefMinutes = $config -> penalty_relief_minutes;
    $penaltyStartTime     = $penaltyReliefModeStartTime -> addMinutes( $penaltyReliefMinutes );
    $penaltyStartTime     = $penaltyStartTime -> timestamp * 1000;

    return $penaltyStartTime;
  }

  /**
   * Calculate charging time in minutes.
   * 
   * @return int
   */
  public function calculateChargingElapsedTimeInMinutes()
  {
    $startChargingTime   = $this -> getStartTimestamp();
    $finishChargingTime  = $this -> getEndTimestamp();

    if( ! $finishChargingTime )
    {
        $finishChargingTime = now();
    }
            
    return $finishChargingTime -> diffInMinutes( $startChargingTime );
  }
}