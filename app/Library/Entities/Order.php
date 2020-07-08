<?php

namespace App\Library\Entities;

use App\Exceptions\NoSuchChargingPriceException;

use App\Enums\PaymentType as PaymentTypeEnum;
use App\Enums\ChargerType as ChargerTypeEnum;
use App\Enums\OrderStatus as OrderStatusEnum;

use App\Enums\ChargingType as ChargingTypeEnum;
use App\Facades\Charger as MishasCharger;
use App\Library\Interactors\Firebase;
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
    private $kiloWattHourLine = 1;

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

        /**
         * TODO: This should be changed when app is in production
         */
        if( $chargerInfo -> chargePointCode == "0110" )
        {
            $kiloWattHour  = $chargerInfo -> kiloWattHour;
        }
        else
        {
            $kiloWattHour  = $chargerInfo -> kiloWattHour / 1000;
        }

        return $kiloWattHour;
    }

    /**
     * Determine if charging is stopped 
     * due to that the car is charged or ether
     * user has used up the money and is in penalty
     * relief mode.
     * 
     * @return bool
     */
    public function enteredPenaltyReliefMode()
    {
        $enteredPenaltyReliefModeTimestamp = $this -> getStopChargingTimestamp();

        return !! $enteredPenaltyReliefModeTimestamp;     
    }

    /**
     * Count money the user has already paid.
     * 
     * @return  float
     * @example 10.25
     */
    public function countPaidMoney()
    {
        if( ! isset( $this -> payments ))
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
     * Count the money user has already consumed(Charged).
     * 
     * @return float
     */
    public function countConsumedMoney()
    {
        $this -> load( 'charger_connector_type' );
        $this -> load( 'payments' );

        if( $this -> hasAlreadyUsedUpMoney() )
        {
            return null;
        }
        
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
        $elapsedMinutes      = $this -> calculateChargingElapsedTimeInMinutes();

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
     * Calculate charging time in minutes.
     * 
     * @return int
     */
    private function calculateChargingElapsedTimeInMinutes()
    {
        $startChargingTime   = $this -> getChargingStatusTimestamp( OrderStatusEnum :: CHARGING );
        
        if( $this -> charger_connector_type -> isChargerFast() )
        {
            $finishChargingTime = $this -> getChargingStatusTimestamp( OrderStatusEnum :: FINISHED );
        }
        else
        {
            $finishChargingTime = $this -> getStopChargingTimestamp();    
        }

        if( ! $finishChargingTime )
        {
            $finishChargingTime = now();
        }
                
        return $finishChargingTime -> diffInMinutes( $startChargingTime );
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
                $consumedMoney += ( $elapsedMinutes - $startMinutes + 1 ) * $price;
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
        $chargingPower      = $this -> kilowatt -> getChargingPower();        
        $startChargingTime  = $this -> getChargingStatusTimestamp( OrderStatusEnum :: CHARGING );
        $startChargingTime  = $startChargingTime -> toTimeString();
        $elapsedMinutes     = $this -> calculateChargingElapsedTimeInMinutes();

        $chargingPriceInfo  = $this 
            -> charger_connector_type 
            -> getSpecificChargingPrice( $chargingPower, $startChargingTime );
        
        if( ! $chargingPriceInfo )
        {
            throw new NoSuchChargingPriceException();
        }

        $chargingPrice = $chargingPriceInfo -> price;
        
        return $chargingPrice * $elapsedMinutes;
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

        if( $this -> hasAlreadyUsedUpMoney() )
        {
            return null;
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
                
        return ( $elapsedMinutes + 1 ) * $penaltyPricePerMinute;    
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
     * Determine if user already used up all the 
     * money he/she typed when charging with BY_AMOUNT.
     * 
     * @return bool
     */
    public function hasAlreadyUsedUpMoney()
    {
        return !! $this -> getChargingStatusTimestamp( OrderStatusEnum :: USED_UP );
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
     * @return Carbon|string
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
        
        $this -> sendFirebaseNotification();
    }

    /**
     * Send firebase notification to user about order update.
     * 
     * @return void
     */
    public function sendFirebaseNotification()
    {
        Firebase :: sendActiveOrders( $this -> user_id );
    }

    /**
     * Update fast charger order.
     * 
     * @return  void
     */
    private function updateFastChargerOrder()
    {
        $chargingStatus = $this -> charging_status;

        if( $chargingStatus == OrderStatusEnum :: CHARGING )
        {
            if( $this -> charging_type == ChargingTypeEnum :: BY_AMOUNT )
            {
                if( $this -> shouldPay() )
                {
                    $charger = $this -> charger_connector_type -> charger;

                    MishasCharger :: stop( 
                        $charger -> charger_id, 
                        $this -> charger_transaction_id 
                    );

                    $this -> updateChargingStatus( OrderStatusEnum :: USED_UP );
                }
            }
            else
            {
                if( $this -> shouldPay() )
                {
                    $this -> pay( PaymentTypeEnum :: CUT, 20 );
                }
            }
        }
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

                    $this -> updateChargingStatus( OrderStatusEnum :: USED_UP );
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
     * Determine if car has already stopped charging.
     * 
     * @return bool
     */
    public function carHasAlreadyStoppedCharging()
    {
        return  $this -> charging_status == OrderStatusEnum :: CHARGED 
            ||  $this -> charging_status == OrderStatusEnum :: USED_UP ;
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

        $chargedTime = $this -> getStopChargingTimestamp();

        if( ! $chargedTime )
        {
            return false;
        }

        $elapsedTime          = $chargedTime -> diffInMinutes( now() );

        return $elapsedTime >= $penaltyReliefMinutes;
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
        $this -> cutOrRefund();
    }

    /**
     * Charge the user or refund
     * accordingly, when lvl 2 charging.
     * 
     * @return void
     */
    private function makeLastPaymentsForLvl2Charging()
    {
        $this -> cutOrRefund();

        if( $this -> isOnPenalty() )
        {
            $penaltyFee     = $this -> countPenaltyFee();   
            $this           -> pay( PaymentTypeEnum :: FINE, $penaltyFee );
        }
    }

    /**
     * Cut/refund or do 
     * nothing according data.
     * 
     * @return void
     */
    private function cutOrRefund()
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
    }

    /**
     * Make transaction.
     * 
     * @param string  $paymentType
     * @param float   $amount
     */
    public function pay( $paymentType, $amount )
    {
        $amount = intval( $amount );

        if( $paymentType == PaymentTypeEnum :: REFUND )
        {
            $payment = new Payment;
            $payment -> refund( $this, $amount );
        }
        else
        {
            $payment = new Payment;
            $payment -> cut( $this, $amount );
        }
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

        $chargingStatus                 = $order -> charging_status;
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