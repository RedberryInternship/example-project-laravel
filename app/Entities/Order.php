<?php

namespace App\Entities;

use App\Exceptions\NoSuchChargingPriceException;

use App\Enums\PaymentType as PaymentTypeEnum;
use App\Enums\ChargerType as ChargerTypeEnum;
use App\Enums\OrderStatus as OrderStatusEnum;

use App\Enums\ChargingType as ChargingTypeEnum;
use App\Facades\Charger as MishasCharger;
use App\Library\Payments\Payment;

use Carbon\Carbon;
use App\Config;

trait Order
{

    /**
     * KiloWattHour line with which we're gonna
     * determine if charging is officially started
     * and if charging is officially ended.
     */
    private $kiloWattHourLine = 2;

    /**
     * Update order charging status.
     * 
     * @param   string $chargingStatus
     * @return  void
     */
    public function updateChargingStatus( $chargingStatus )
    {
        $this -> charging_status = $chargingStatus;
        $this -> save();
    }

    /**
     * Get charging power.
     * 
     * @return float
     */
    public function getChargingPower()
    {
        $chargerInfo   = MishasCharger :: transactionInfo( $this -> charger_transaction_id );
        $kiloWattHour  = $chargerInfo -> kiloWattHour;

        return $kiloWattHour;
    }

    /**
     * Count money the user has already paid.
     * 
     * @return  float
     * @example 10.25
     */
    public function countPaidMoney()
    {
        if( ! isset($this -> payments ))
        {
            $this -> load( 'payments' );
        }

        if( count( $this -> payments ) == 0 )
        {
            return 0.0;
        }
    
        $paidMoney = $this 
            -> payments 
            -> where( 'type', PaymentTypeEnum :: CUT ) 
            -> sum( 'price' );

        $paidMoney = round        ( $paidMoney, 2 );
        
        return $paidMoney;
    }

    /**
     * Count money the user has already paid with fines.
     * 
     * @return  float
     * @example 10.25
     */
    public function countPaidMoneyWithFine()
    {
        if( ! isset($this -> payments ))
        {
            $this -> load( 'payments' );
        }

        if( count( $this -> payments ) == 0 )
        {
            return 0.0;
        }
    
        $paidCuts = $this 
            -> payments 
            -> where    ( 'type', PaymentTypeEnum :: CUT  )
            -> sum( 'price' );

        $paidFines = $this 
            -> payments 
            -> where    ( 'type', PaymentTypeEnum :: FINE )
            -> sum( 'price' );

        $paidMoney = $paidFines + $paidCuts;
        $paidMoney = round        ( $paidMoney, 2 );

        return $paidMoney;
    }

    /**
     * Count the money user has already consumed(Charged).
     * 
     * @return float
     */
    public function countConsumedMoney()
    {
        $this -> load( 'charger_connector_type' );
        $this -> load( 'payments' );

        
        if( count( $this -> payments ) == 0 )
        {
            return 0.0;
        }
        
        $chargerType = $this -> charger_connector_type -> determineChargerType();
        
        $consumedMoney = $chargerType == ChargerTypeEnum :: FAST 
            ? $this -> countConsumedMoneyByTime()
            : $this -> countConsumedMoneyByKilowatt();
        
        $consumedMoney = round        ( $consumedMoney, 2 );

        return $consumedMoney;
    }

    /**
     * Counting consumed money when charger type is FAST.
     * 
     * @return float
     */
    private function countConsumedMoneyByTime()
    {
        $startChargingTime   = $this -> getChargingStatusTimestamp( OrderStatusEnum :: CHARGING );
        $finishChargingTime  = $this -> getChargingStatusTimestamp( OrderStatusEnum :: FINISHED );

        if( $finishChargingTime )
        {
            $elapsedMinutes      = $finishChargingTime -> diffInMinutes( $startChargingTime );
        }
        else
        {
            $elapsedMinutes      = now() -> diffInMinutes( $startChargingTime );
        }

        $chargingPriceRanges =  $this 
            -> charger_connector_type
            -> collectFastChargingPriceRanges( $elapsedMinutes );

        $consumedMoney = $this -> accumulateFastChargerConsumedMoney( 
            $chargingPriceRanges, 
            $elapsedMinutes,
         );
       
        return $consumedMoney;
    }

    /**
     * Accumulate fast charger consumed money
     * based on elapsed minutes.
     * 
     * @param Collection $chargingPriceRanges
     * @param int        $elapsedMinutes
     */
    private function accumulateFastChargerConsumedMoney( $chargingPriceRanges, $elapsedMinutes )
    {
        $consumedMoney          = 0;

        $chargingPriceRanges -> each( function ( $chargingPriceInstance ) 
        use ( &$consumedMoney, $elapsedMinutes ) {
            
            $startMinutes       = $chargingPriceInstance -> start_minutes;
            $endMinutes         = $chargingPriceInstance -> end_minutes;
            $price              = $chargingPriceInstance -> price;
            $minutesInterval    = $endMinutes - $startMinutes + 1;

            if( $elapsedMinutes > $chargingPriceInstance -> end_minutes)
            {
                $consumedMoney += $price * $minutesInterval;
            }
            else
            {
                $consumedMoney += ($elapsedMinutes - $startMinutes + 1 ) * $price;
            }
        });

        return $consumedMoney;
    }

    /**
     * Counting consumed money when charger type is LVL2.
     * 
     * @return float
     */
    private function countConsumedMoneyByKilowatt()
    {
        $consumedKilowatts  = $this -> kilowatt -> consumed;
        $chargingPower      = $this -> kilowatt -> getChargingPower();
        
        $startChargingTime  = $this -> getChargingStatusTimestamp( OrderStatusEnum :: CHARGING );
        $startChargingTime  = $startChargingTime -> toTimeString();

        $chargingPriceInfo  = $this 
            -> charger_connector_type 
            -> getSpecificChargingPrice( $chargingPower, $startChargingTime );
        
        if( ! $chargingPriceInfo )
        {
            throw new NoSuchChargingPriceException();
        }

        $chargingPrice = $chargingPriceInfo -> price;
        $consumedMoney = ( $consumedKilowatts / $chargingPower ) * $chargingPrice;
        
        return $consumedMoney;
    }
 
    /**
     * Count money to refund the user.
     * 
     * @return float
     */
    public function countMoneyToRefund()
    {
        if( count( $this -> payments ) == 0 )
        {
            return 0.0;
        }

        $moneyToRefund = $this -> countPaidMoney() - $this -> countConsumedMoney();
        $moneyToRefund = round( $moneyToRefund, 2 );
    
        return $moneyToRefund;
    }

    /**
     * Count money to cut.
     * 
     * @return float
     */
    public function countMoneyToCut()
    {
        $consumedMoney  = $this -> countConsumedMoney();
        $alreadyPaid    = $this -> countPaidMoney();
        $moneyToCut     = $consumedMoney - $alreadyPaid;
        
        return $moneyToCut;
    }

    /**
     * Count money to refund with penalty fee.
     * 
     * @return float
     */
    public function countPenaltyFee()
    {
        $penaltyTimestamp       = $this -> getPenaltyTimestamp();
        $finishedTimestamp      = $this -> getChargingStatusTimestamp( OrderStatusEnum :: FINISHED );

        if( ! $finishedTimestamp )
        {
            $finishedTimestamp  = now();
        }
        
        $elapsedMinutes         = $penaltyTimestamp -> diffInMinutes( $finishedTimestamp );
        $penaltyPricePerMinute  = $this -> getPenaltyPricePerMinute();
                
        return $elapsedMinutes * $penaltyPricePerMinute;    
    }

    /**
     * Get penalty price per minute.
     * 
     * @return float
     */
    private function getPenaltyPricePerMinute()
    {
        $config                 = Config :: first();
        $penaltyPricePerMinute  = $config -> penalty_price_per_minute;
        
        return $penaltyPricePerMinute; 
    }

    /**
     * Get penalty timestamp.
     * 
     * @return null|string
     */
    private function getPenaltyTimestamp()
    {
        $penaltyTimestamp = $this -> getChargingStatusTimestamp( OrderStatusEnum :: ON_FINE );
        
        return $penaltyTimestamp;
    }

    /**
     * Determine if user is on penalty.
     * 
     * @return bool
     */
    public function isOnPenalty()
    {
        $isOnPenalty = !! $this -> getPenaltyTimestamp();

        return $isOnPenalty;
    }

    /**
     * Update charging information and make 
     * transactions.
     * 
     * @return void
     */
    public function chargingUpdate()
    {
        $chargerType = $this -> charger_connector_type -> determineChargerType();

        $chargerType == ChargerTypeEnum :: FAST
            ? $this -> updateFastChargerOrder()
            : $this -> updateLvl2ChargerOrder(); 
    }

    /**
     * Update fast charger order.
     * 
     * @return  void
     */
    private function updateFastChargerOrder()
    {
        // TODO: Implement
    }

    /**
     * Update Lvl 2 charger order.
     * 
     * @return  void
     */
    private function updateLvl2ChargerOrder()
    {
        $chargingStatus = $this -> charging_status;

        switch( $chargingStatus )
        {
        case OrderStatusEnum :: INITIATED :

            if( $this -> chargingHasStarted() )
            {
                $this -> updateChargingPower();
                $this -> updateChargingStatus( OrderStatusEnum :: CHARGING );   
                
                if( $this -> charging_type == ChargingTypeEnum :: BY_AMOUNT )
                {
                    $this -> pay( PaymentTypeEnum :: CUT, $this -> target_price );
                }
                else
                {
                    $this -> pay( PaymentTypeEnum :: CUT, 20 );
                }
            }       
        break;
        
        case OrderStatusEnum :: CHARGING :

            if( $this -> charging_type == ChargingTypeEnum :: BY_AMOUNT )
            {
                if( $this -> shouldPay() )
                {
                    $charger = $this -> charger_connector_type -> charger;

                    MishasCharger :: stop( 
                        $charger -> charger_id, 
                        $this -> charger_transaction_id 
                    );

                    $this -> updateChargingStatus( OrderStatusEnum :: CHARGED );
                }
            }
            else
            {
                if( $this -> shouldPay() )
                {
                    $this -> pay( PaymentTypeEnum :: CUT, 20 );
                }
    
                if( $this -> carHasAlreadyCharged() )
                {
                    $this -> updateChargingStatus( OrderStatusEnum :: CHARGED );
                } 
            }

        break;
        
        case OrderStatusEnum :: CHARGED :
            if( $this -> isOnFine() ) 
            {
                $this -> updateChargingStatus( OrderStatusEnum :: ON_FINE); 
            }
        break;
        }
    }

    /**
     * Determine if charging has officially started.
     * 
     * @param   float $kiloWattHour
     * @return  bool
     */
    private function chargingHasStarted()
    {
        $chargingPower    = $this  -> getChargingPower();
        $kiloWattHourLine = $this  -> kiloWattHourLine;

        return $chargingPower > $kiloWattHourLine;
    }

    /**
     * Update kilowatt charging power.
     * 
     * @return void
     */
    private function updateChargingPower()
    {
        $chargingPower  = $this -> getChargingPower();

        $this -> kilowatt -> setChargingPower( $chargingPower );
    }

    /**
     * Determine if car is charged.
     * 
     * @param \App\Order $order
     * @return bool
     */
    public function carHasAlreadyCharged()
    {
        $chargingPower    = $this  -> getChargingPower();
        $kiloWattHourLine = $this  -> kiloWattHourLine;

        return $chargingPower < $kiloWattHourLine;
    }

    /**
     * Determine if order is on fine.
     * 
     * @param \App\Order $order
     * @return bool
     */
    public function isOnFine()
    {
        $config               = Config :: first();
        $penaltyReliefMinutes = $config -> penalty_relief_minutes;

        $chargedTime          = $this -> getChargingStatusTimestamp( OrderStatusEnum :: CHARGED ); 
        $chargedTime          = Carbon :: create( $chargedTime );

        $elapsedTime          = $chargedTime -> diffInMinutes( now() );

        return $elapsedTime > $penaltyReliefMinutes;
    }

    /**
     * Determine if consumed money is above 
     * the paid currency.
     * 
     * @return bool
     */
    private function shouldPay()
    {
        $paidMoney      = $this -> countPaidMoney();
        $consumedMoney  = $this -> countConsumedMoney();
        
        return  $consumedMoney > $paidMoney;
    }

    /**
     * Determine if paid money is less 
     * then consumed money.
     * 
     * @return bool
     */
    private function shouldRefund()
    {
        $paidMoney      = $this -> countPaidMoney();
        $consumedMoney  = $this -> countConsumedMoney();
        
        return  $consumedMoney < $paidMoney;
    }

    /**
     * Finish order by making last payments
     * updating charging status to FINISHED.
     * 
     * @return void
     */
    public function finish()
    {
        $chargerType = $this -> charger_connector_type -> determineChargerType();

        $chargerType == ChargerTypeEnum :: FAST
        ? $this -> makeLastPaymentsForFastCharging()
        : $this -> makeLastPaymentsForLvl2Charging();

        $this -> updateChargingStatus( OrderStatusEnum :: FINISHED );
    }

    /**
     * Charge the user or refund
     * accordingly, when fast charging.
     * 
     * @return void
     */
    private function makeLastPaymentsForFastCharging()
    {
        // TODO: Implement
    }

    /**
     * Charge the user or refund
     * accordingly, when lvl 2 charging.
     * 
     * @return void
     */
    private function makeLastPaymentsForLvl2Charging()
    {
        if( $this -> shouldPay() )
        {
            $shouldCutMoney = $this -> countMoneyToCut();
            $this           -> pay( PaymentTypeEnum :: CUT, $shouldCutMoney );
        }
        else if( $this -> shouldRefund() )
        {
            $moneyToRefund  = $this -> countMoneyToRefund();
            $this           -> pay( PaymentTypeEnum :: REFUND, $moneyToRefund );
        }

        if( $this -> isOnPenalty() )
        {
            $penaltyFee     = $this -> countPenaltyFee();   
            $this           -> pay( PaymentTypeEnum :: FINE, $penaltyFee );
        } 
    }

    /**
     * Make transaction.
     * 
     * @param string  $paymentType
     * @param float   $amount
     */
    private function pay( $paymentType, $amount )
    {
        $userCard = $this -> user_card ;
        Payment :: pay( $this, 20, $paymentType );

        $this -> payments() -> create(
            [
            'type'          => $paymentType,
            'confirmed'     => true,
            'confirm_date'  => now(),
            'price'         => $amount,
            'prrn'          => 'SOME_PRRN',
            'trx_id'        => 'SOME_TRIX_ID',
            'user_card_id'  => $userCard -> id,
            ]
        );
    }

    /** 
     * Set charging status change dates initial value 
     * when creating.
     * USED IN MODEL HOOKS.
     * 
     * @param \App\Order $order
     * @return void
     */
    public static function setChargingStatusInitialDates( $order ) 
    {
        $availableOrderStatuses = OrderStatusEnum :: getConstantsValues();
        $initialStatuses        = [];

        $now = app() -> runningUnitTests() ? now() -> timestamp : microtime( true );

        foreach( $availableOrderStatuses as $status )
        {
            $initialStatuses [ $status ] = null;
        }

        if( $order -> charging_status == OrderStatusEnum :: INITIATED )
        {
            $initialStatuses [ OrderStatusEnum :: INITIATED ] = $now;
        }
        else
        {
            $initialStatuses [ OrderStatusEnum :: CHARGING ]  = $now;
        }

        $order -> charging_status_change_dates = $initialStatuses;
    }


    /**
     * Set charging status change dates if not set,
     * when updating.
     * USED IN MODEL HOOKS.
     */
    public static function updateChargingStatusChangeDates( $order )
    {
        $now = app() -> runningUnitTests() ? now() -> timestamp : microtime( true );

        $chargingStatus = $order -> charging_status;
        $orderChargingStatusChargeDates = $order -> charging_status_change_dates; 

        if( ! $orderChargingStatusChargeDates [ $chargingStatus ] )
        {
            $orderChargingStatusChargeDates [ $chargingStatus ] = $now;
            $order -> charging_status_change_dates = $orderChargingStatusChargeDates;
        }
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