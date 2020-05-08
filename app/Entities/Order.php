<?php

namespace App\Entities;

use App\Exceptions\NoSuchFastChargingPriceException;
use App\Exceptions\NoSuchChargingPriceException;

use App\Enums\PaymentType as PaymentTypeEnum;
use App\Enums\ChargerType as ChargerTypeEnum;
use App\Enums\OrderStatus as OrderStatusEnum;

use App\Facades\Charger as MishasCharger;
use App\Library\Payments\Payment;

use App\FastChargingPrice;
use App\ChargingPrice;
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
        $statChargingTime           = $this -> payments -> first() -> confirm_date;
        $elapsedMinutes             = now() -> diffInMinutes( $statChargingTime );
        
        $chargingPrice   = FastChargingPrice :: where(
            [
                [ 'charger_connector_type_id', $this -> charger_connector_type -> id ],
                [ 'start_minutes',  '<='     , $elapsedMinutes ],
                [ 'end_minutes',    '>='     , $elapsedMinutes ],
            ]
        ) -> first();

        if( ! $chargingPrice )
        {
            throw new NoSuchFastChargingPriceException();
        }

        return $chargingPrice -> price;
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
        
        $startChargingTime  = $this -> payments -> first() -> confirm_date;
        $startChargingTime  = Carbon :: create( $startChargingTime );
        $startChargingTime  = $startChargingTime -> toTimeString();

        $rawSql             = $this -> getTimeBetweenSqlQuery( $startChargingTime );

        $chargingPriceInfo  = ChargingPrice :: where(
                [
                    [ 'charger_connector_type_id',  $this -> charger_connector_type -> id ],
                    [ 'min_kwt', '<='            ,  $chargingPower ],
                    [ 'max_kwt', '>='            ,  $chargingPower ],
                ]
            )
            -> whereRaw( $rawSql )
            -> first();
        
        if( ! $chargingPriceInfo )
        {
            throw new NoSuchChargingPriceException();
        }

        $chargingPrice = $chargingPriceInfo -> price;
        $consumedMoney = ( $consumedKilowatts / $chargingPower ) * $chargingPrice;
        
        return $consumedMoney;
    }

    /**
     * Get time between sql raw query.
     * 
     * @param   time $startChargingTime
     * @return  string
     */
    private function getTimeBetweenSqlQuery( $startChargingTime )
    {
        $rawSql = 'CAST( start_time as time ) '
        . '<=   CAST( "'. $startChargingTime .'" as time  )'
        . 'AND  CAST( "'. $startChargingTime .'" as time  )'
        . '<=   CAST( end_time as time )';

        return $rawSql;
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
        $chargingStatusChangeDates = $this -> charging_status_change_dates;
        $penaltyTimestamp          = $chargingStatusChangeDates [ OrderStatusEnum :: ON_FINE ];
        
        if( $penaltyTimestamp )
        {
            $penaltyTimestamp      = Carbon::create( $penaltyTimestamp );
        }
        
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
        
        return Carbon :: create( $statusTimestamp );
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
            $this -> pay( PaymentTypeEnum :: CUT, 20 );
            }       
        break;
        
        case OrderStatusEnum :: CHARGING :

            if( $this -> shouldPay() )
            {
                $this -> pay( PaymentTypeEnum :: CUT, 20 );
            }

            if( $this -> carHasAlreadyCharged() )
            {
            $this -> updateChargingStatus( OrderStatusEnum :: CHARGED );
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

        $chargedTime          = $this -> updated_at; // Dont find out like this 
        $chargedTime          = Carbon :: create( $chargedTime );

        $elapsedTime          = $chargedTime -> diffInMinutes( now() );

        return $elapsedTime > $penaltyReliefMinutes;
    }

    /**
     * Determine if consumed kilowatt money is above 
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
        $consumedMoney = $this -> countConsumedMoney();
        $alreadyPaid   = $this -> countPaidMoney();
        
        if( $consumedMoney > $alreadyPaid )
        {
        $shouldCutMoney = $consumedMoney - $alreadyPaid;
        $this -> pay( PaymentTypeEnum :: CUT, $shouldCutMoney );
        }
        else if( $consumedMoney < $alreadyPaid )
        {
        $moneyToRefund = $this -> countMoneyToRefund();
        $this -> pay( PaymentTypeEnum :: REFUND, $moneyToRefund );
        }

        if( $this -> isOnPenalty() )
        {
        $penaltyFee = $this -> countPenaltyFee();

        $this -> pay( PaymentTypeEnum :: FINE, $penaltyFee );
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
     * 
     * @param \App\Order $order
     * @return void
     */
    public static function setChargingStatusInitialDates( $order ) {

        $availableOrderStatuses = OrderStatusEnum :: getConstantsValues();
        $initialStatuses        = [];

        foreach( $availableOrderStatuses as $status )
        {
            $initialStatuses [ $status ] = null;
        }

        if( $order -> charging_status == OrderStatusEnum :: INITIATED )
        {
            $initialStatuses [ OrderStatusEnum :: INITIATED ] = now();
        }
        else
        {
            $initialStatuses [ OrderStatusEnum :: CHARGING ]  = now();
        }

        $order -> charging_status_change_dates = $initialStatuses;
    }

    /**
     * Set charging status change dates if not set,
     * when updating.
     */
    public static function updateChargingStatusChangeDates( $order )
    {
        $chargingStatus = $order -> charging_status;
        $orderChargingStatusChargeDates = $order -> charging_status_change_dates; 

        if( ! $orderChargingStatusChargeDates [ $chargingStatus ] )
        {
            $orderChargingStatusChargeDates [ $chargingStatus ] = now();
            $order -> charging_status_change_dates = $orderChargingStatusChargeDates;
        }
    }
}