<?php

namespace App\Library\Entities;

use App\Enums\PaymentType as PaymentTypeEnum;
use App\Enums\ChargerType as ChargerTypeEnum;
use App\Enums\OrderStatus as OrderStatusEnum;

use App\Library\Entities\ChargingProcess\CacheOrderDetails;
use App\Enums\ChargingType as ChargingTypeEnum;
use App\Facades\Charger as RealCharger;
use App\Library\Interactors\Firebase;
use App\Library\Interactors\Payment;
use App\Facades\Simulator;
use App\Helpers\App;
use App\Config;
use App\User;

trait Order
{
    /**
     * Lock payments.
     * 
     * @return void
     */
    public function lockPayments()
    {
        $this -> lock_payments = true;
        $this -> save();
    }

    /**
     * Unlock payments.
     * 
     * @return void
     */
    public function unlockPayments()
    {
        $this -> lock_payments = false;
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

        # GLITCH
        if(App :: dev() && $chargerInfo -> chargePointCode != "0110")
        {
            return $chargerInfo -> kiloWattHour / 1000;
        }

        return $chargerInfo -> kiloWattHour;
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
        CacheOrderDetails :: execute( $this );
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
        $this -> updateChargingPowerIfNotUpdated();
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
                    
                    # GLITCH
                    if(App :: dev())
                    {
                        Simulator :: plugOffCable( $charger -> charger_id );
                    }
                }
            }
            else
            {
                if( $this -> shouldPay() )
                {
                    $moneyToCut = Config :: nextChargePrice();
                    $this -> pay( PaymentTypeEnum :: CUT, $moneyToCut );
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
                $this -> updateChargingPowerIfNotUpdated();
                $this -> updateChargingStatus( OrderStatusEnum :: CHARGING );   
                

                if( ! $this -> isChargingFree() )
                {
                    if( $this -> charging_type == ChargingTypeEnum :: BY_AMOUNT )
                    {
                        $this -> pay( PaymentTypeEnum :: CUT, $this -> target_price );
                    }
                    else
                    {
                        $moneyToCut = Config :: initialChargePrice();
                        $this -> pay( PaymentTypeEnum :: CUT, $moneyToCut );
                    }
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
                if( $this -> shouldPay() && (! $this -> isChargingFree()) )
                {
                    $moneyToCut = Config :: nextChargePrice();
                    $this -> pay( PaymentTypeEnum :: CUT, $moneyToCut );
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
    private function updateChargingPowerIfNotUpdated()
    {
        if( ! $this -> kilowatt -> charging_power )
        {
            $chargingPower  = $this -> getChargingPower();
            $this -> kilowatt -> setChargingPower( $chargingPower );
        }
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
        
        $this -> updateFinishedTimestamp();
        
        $chargerType == ChargerTypeEnum :: FAST
            ? $this -> makeLastPaymentsForFastCharging()
            : $this -> makeLastPaymentsForLvl2Charging();
        
        if( $this -> canGoToFinishStatus( $this -> charging_status ) )
        {
            $this -> updateChargingStatus( OrderStatusEnum :: FINISHED );
            Firebase :: sendFinishNotificationWithData( $this -> charger_transaction_id );
        }
        
        CacheOrderDetails :: execute( $this );
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
        if( ! $this -> isChargingFree() )
        {
            $this -> cutOrRefund();
        }

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
        if(! App :: dev())
        {
            $amount = $amount * 100;
        }

        if( $this -> shouldContinueTransaction( $paymentType ) )
        {
            return;
        }

        $this -> lockPaymentsIfNecessary( $paymentType );

        switch( $paymentType )
        {
            case PaymentTypeEnum :: REFUND  : return Payment :: refund ( $this, $amount );
            case PaymentTypeEnum :: FINE    : return Payment :: charge ( $this, $amount );
            case PaymentTypeEnum :: CUT     : return Payment :: cut    ( $this, $amount );
        }
    }

    /**
     * Determine if payments are locked.
     * 
     * @return bool
     */
    private function isPaymentLocked()
    {
        return $this -> lock_payments;
    }

    /**
     * Lock payments if necessary.
     * 
     * @param  string $paymentType
     * @return void
     */
    private function lockPaymentsIfNecessary( $paymentType )
    {
        if( $paymentType != PaymentTypeEnum :: REFUND )
        {
            $this -> lockPayments();
        }
    }

    /**
     * Determine if transaction should be continued.
     * 
     * @return bool
     */
    private function shouldContinueTransaction( $paymentType ): bool
    {
        return $this -> isPaymentLocked() && $paymentType != PaymentTypeEnum :: REFUND;
    }

    /**
     * Set finished timestamp.
     * 
     * @return void
     */
    public function updateFinishedTimestamp(): void
    {
        $this -> update(
            [
                'real_end_date' => $this -> getRealFinishedTimestamp(),
            ]
        );
    }

    /**
     * Get charging finished timestamp.
     * 
     * @return string|null
     */
    private function getRealFinishedTimestamp()
    {
        return RealCharger :: transactionInfo( $this -> charger_transaction_id ) -> transStop / 1000;
    }
}