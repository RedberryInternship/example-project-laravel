<?php

namespace App\Library\Entities;

use App\Enums\PaymentType as PaymentTypeEnum;
use App\Enums\ChargerType as ChargerTypeEnum;
use App\Enums\OrderStatus as OrderStatusEnum;

use App\Enums\ChargingType as ChargingTypeEnum;
use App\Facades\Charger as RealCharger;
use App\Library\Interactors\Firebase;
use App\Library\Interactors\Payment;

use App\Config;

trait Order
{
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
        $chargerInfo   = RealCharger :: transactionInfo( $this -> charger_transaction_id );

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

                    RealCharger :: stop( 
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

            $charger = $this -> charger_connector_type -> charger;

            if( $this -> charging_type == ChargingTypeEnum :: BY_AMOUNT )
            {
                if( $this -> shouldPay() )
                {
                    RealCharger :: stop( 
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
                    RealCharger :: stop( 
                        $charger -> charger_id, 
                        $this -> charger_transaction_id 
                    );

                    $this -> updateChargingStatus( OrderStatusEnum :: CHARGED );
                } 
            }
        }
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

        switch( $paymentType )
        {
            case PaymentTypeEnum :: REFUND  : return Payment :: refund ( $this, $amount );
            case PaymentTypeEnum :: FINE    : return Payment :: charge ( $this, $amount );
            case PaymentTypeEnum :: CUT     : return Payment :: cut    ( $this, $amount );
        }
    }
}