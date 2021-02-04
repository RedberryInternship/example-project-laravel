<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;
use App\Traits\Message;
use App\Payment as PaymentModel;
use App\Library\Entities\Helper;
use App\Library\Interactors\Payment;
use App\Facades\Charger as RealCharger;
use App\Enums\PaymentType as PaymentTypeEnum;
use App\Enums\OrderStatus as OrderStatusEnum;
use App\Enums\ChargerType as ChargerTypeEnum;
use App\Enums\ChargingType as ChargingTypeEnum;
use App\Library\Entities\ChargingProcess\Hook;
use App\Exceptions\NoSuchChargingPriceException;
use App\Library\Entities\ChargingProcess\Timestamp;

class Order extends Model
{
    use Message;

    /*************************************************
     * 
     * ===!> Orders model attribute definitions <!=== 
     * 
     ************************************************
     */

    /**
     * Laravel guarded attribute.
     * 
     * @var array $guarded
     */
    protected $guarded = [];

    /**
     * Laravel casts attribute.
     * 
     * @var array $casts
     */
    protected $casts = [
        'charging_status_change_dates' => 'array',
    ];

    /************************************** 
     * 
     * ===!> Laravel model hooks <!=== 
     * 
     **************************************
     */

    /**
     * override model boot in order to add hooks.
     * 
     * @return void
     */
    public static function boot()
    {
        parent :: boot();

        static :: creating([ Hook :: class, 'setChargingStatusInitialDates'     ]);
        static :: updating([ Hook :: class, 'updateChargingStatusChangeDates'   ]);
    }


    /******************************************
     * 
     * ===!> Laravel model Relationships <!=== 
     * 
     ******************************************
     */

    /**
     * Order belongsTo relationship with User.
     * 
     * @return App\User
     */
    public function user()
    {
    	return $this -> belongsTo( User :: class );
    }

    /**
     * Order hasMany relationship with Payment.
     * 
     * @return Illuminate\Support\Collection
     */
    public function payments()
    {
        return $this -> hasMany( PaymentModel :: class );
    }

    /**
     * Order hasMany relationship with OrderChargingPower;
     * 
     * @return Illuminate\Support\Collection
     */
    public function charging_powers() 
    {
        return $this -> hasMany(ChargingPower :: class);
    }

    /**
     * Order belongsTo relationship with UserCard.
     * 
     * @return UserCard 
     */
    public function user_card()
    {
        return $this -> belongsTo( UserCard :: class );   
    }
    
    /**
     * Order belongsTo relationship with ChargerConnectorType
     * 
     * @return App\ChargerConnectorType
     */
    public function charger_connector_type()
    {
        return $this -> belongsTo( ChargerConnectorType :: class );
    }

    /**
     * Order HasOne relationship with Kilowatt.
     * 
     * @return App\Kilowatt
     */
    public function kilowatt()
    {
        return $this -> hasOne( Kilowatt :: class );
    }

    /********************************************* 
     * 
     * ===!> Orders model query scopes <!=== 
     * 
     *********************************************
     */

    /**
     * Get orders with confirmed payments.
     * 
     * @param   Builder
     * @return  Builder
     */
    public function scopeConfirmedPayments( $query )
    {
        return $query -> with([ 'payments' => function ( $q ) {
            return $q -> confirmed();
        }]);
    }

    /**
     * Get orders with confirmed payments(with user cards).
     * 
     * @param   Builder
     * @return  Builder
     */
    public function scopeConfirmedPaymentsWithUserCards( $query )
    {
        return $query -> with([ 'payments' => function ( $q ) {
            return $q -> confirmed() -> withUserCards();
        }]);
    }

    /**
     * Get active orders.
     * 
     * @param Builder
     * @return Builder
     */
    public function scopeActive( $query )
    {
        return $query -> whereIn( 'charging_status', [
            OrderStatusEnum :: INITIATED,
            OrderStatusEnum :: CHARGING,
            OrderStatusEnum :: CHARGED,
            OrderStatusEnum :: USED_UP,
            OrderStatusEnum :: ON_FINE,
            OrderStatusEnum :: ON_HOLD,
        ]);
    }
    
    /**
     * Get active orders.
     * 
     * @param Builder
     * @return Builder
     */
    public function scopeFinished( $query )
    {
        return $query -> where( 'charging_status', OrderStatusEnum :: FINISHED );
    }

    /**
     * Orders from new version of the application.
     * 
     * @param Builder
     * @return void
     */
    public function scopeWithoutOld( $query )
    {
        return $query -> where('id', '>', 9664);
    }

    /**
     * Filter orders by start date.
     * 
     * @param Builder $query
     * @return void
     */
    public function scopeFilterByStartDate( $query )
    {  
        $date = request() -> get( 'start_date' );
        if( $date )
        {
            try 
            {
                $date = Carbon :: parse( $date );
                $query -> whereDate( 'created_at', '>=', $date );
            }
            catch( \Exception $e )
            {
                // Do nothing, it's ok.
            }
        }
    }

    /**
     * Filter orders by end date.
     * 
     * @param Builder $query
     * @return void
     */
    public function scopeFilterByEndDate( $query )
    {
        $date = request() -> get( 'end_date' );

        if( $date )
        {
            try 
            {
                $date = Carbon :: parse($date);
                $query -> whereDate( 'created_at', '<=', $date );
            }
            catch ( \Exception $e )
            {
                // Do nothing, it's ok.
            }
        }
    }

    /**
     * Filter orders by charging type.
     * 
     * @param Builder $query
     * @return void
     */
    public function scopeFilterByChargerType( $query )
    {
        $chargerType = request() -> get( 'charger_type' );
        
        if( $chargerType )
        {   
            $chargerIds = $chargerType === 'FAST' ? Charger :: getFastIds() : Charger :: getLvl2Ids();

            $query -> whereHas('charger_connector_type.charger', function( $q ) use( $chargerIds ) {
                $q -> whereIn('id', $chargerIds);
            });
        }
    }

    /**
     * Filter by search word.
     * 
     * @param Builder $query
     * @return void
     */
    public function scopeFilterBySearchWord( $query )
    {
        $word = request() -> get( 'search' );

        if( $word )
        {
            $query 
                -> where( 'id', 'like', '%'. $word .'%' )
                -> orWhereHas( 'charger_connector_type.charger', function( $q ) use( $word ) {
                    $q 
                        -> where( 'code', 'like', '%'. $word .'%')
                        -> orWhere( 'location->ka', 'like', '%'. $word .'%' )
                        -> orWhere( 'location->ka', 'like', '%'. $word .'%' )
                        -> orWhere( 'location->ru', 'like', '%'. $word .'%' )
                        -> orWhere( 'location->en', 'like', '%'. $word .'%' );
                })
                -> orWhereHas( 'user', function( $q ) use( $word ) {
                    $q 
                        -> where( 'first_name', 'like', '%'. $word .'%' )
                        -> orWhere( 'last_name', 'like', '%'. $word .'%' )
                        -> orWhere( 'phone_number', 'like', '%'. $word .'%' );
                });

        }
    }

    /**
     * Scope for filtering business transactions.
     * 
     * @param Builder $query
     * @return Builder
     */
    public function scopeFilterBusinessTransactions( $query )
    {
        $query 
            -> finished()
            -> with(
                [
                    'charger_connector_type.charger',
                    'user_card',
                    'payments',
                    'user',
                ]
            ) 
            -> whereHas('charger_connector_type.charger', function($query) {
                $query -> whereNotNull('chargers.company_id');
                $query -> where('chargers.company_id', auth() -> user() -> company_id);
            })
            -> filterByStartDate()
            -> filterByEndDate()
            -> filterByChargerType()
            -> filterBySearchWord()
            -> orderBy( 'id', 'DESC' );
    }

    /******************************************************* 
     * 
     * ===!> Attribute Methods: setters, getters, etc. <!=== 
     * 
     *******************************************************
     */
    
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
     * Determine if payments are locked.
     * 
     * @return bool
     */
    private function isPaymentLocked()
    {
        return $this -> lock_payments;
    }

    /**
     * Determine if charging type is BY_AMOUNT.
     * 
     * @return bool
     */
    public function isByAmount(): bool
    {
        return $this -> charging_type == ChargingTypeEnum :: BY_AMOUNT;
    }

    /**
     * Determine if order is active
     * and the status is initiated.
     * 
     * @return bool
     */
    public function isInitiated(): bool
    {
        return $this -> charging_status == OrderStatusEnum :: INITIATED;
    }
    
    /**
     * Determine if order has already initiated.
     * 
     * @return bool
     */
    public function hasInitiated(): bool
    {
        $initiatedTimestamp = @$this -> charging_status_change_dates[OrderStatusEnum :: INITIATED];
        
        return $initiatedTimestamp !== null && !$this ->isInitiated();
    }

    /**
     * Determine if order is active
     * and the status is charging.
     * 
     * @return bool
     */
    public function isCharging(): bool
    {
        return $this -> charging_status == OrderStatusEnum :: CHARGING;
    }
    
    /**
     * Determine if order is active
     * and the status is charging.
     * 
     * @return bool
     */
    public function isFinished(): bool
    {
        return $this -> charging_status == OrderStatusEnum :: FINISHED;
    }

    /********************************************* 
     * 
     * ===!> Orders model helper methods <!=== 
     * 
     *********************************************
     */

    /**
     * KiloWattHour line with which we're gonna
     * determine if charging is officially started
     * and if charging is officially ended.
     */
    private $kiloWattHourLine = .1;

    /**
     * Get order charger through 
     * charger connector type relationship.
     * 
     * @return Charger 
     */
    public function getCharger(): Charger
    {
        return $this -> charger_connector_type -> charger;
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
     * Determine if charging price is zero a.k.a. free.
     * 
     * @return bool
     */
    public function isChargingFree()
    {
        if( $this -> charger_connector_type -> isChargerFast() )
        {
            return false;
        }

        $currentChargingPrice = $this -> getCurrentChargingPrice();

        if( is_null( $currentChargingPrice ) )
        {
            return $currentChargingPrice;
        }
        
        return $currentChargingPrice == 0;
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

    /**
     * Get charging power.
     * 
     * @return float
     */
    public function getChargingPower()
    {
        $chargerInfo   = RealCharger :: transactionInfo( $this -> charger_transaction_id );

        # GLITCH
        if(Helper :: isDev() && $chargerInfo -> chargePointCode != "0110")
        {
            return $chargerInfo -> kiloWattHour / 1000;
        }

        return $chargerInfo -> kiloWattHour;
    }

    /**
     * Update kilowatt charging power.
     * 
     * @return void
     */
    public function updateChargingPowerIfNotUpdated()
    {
        if( ! $this -> kilowatt -> charging_power )
        {
            $chargingPower  = $this -> getChargingPower();
            $this -> kilowatt -> setChargingPower( $chargingPower );
        }

        if( ! $this -> hasInitiated() )
        {
            return;
        }

        $latestChargingPower = $this 
            -> charging_powers()
            -> where( 'order_id', $this -> id )
            -> orderBy('id', 'desc')
            -> first();
        
        if( $latestChargingPower ) 
        {
            $startedAt = (int) $latestChargingPower -> start_at;
            $diff = now() -> timestamp - $startedAt;

            if( $diff < 60 * 5 )
            {
                return;
            }

            $latestChargingPower -> update([ 'end_at' => now() -> timestamp ]);
        }

        $currentChargingPower = $this -> getChargingPower();
        $chargingPrice        = $this -> getChargingPrice( $currentChargingPower );

        $this 
            -> charging_powers()
            -> create(
                [
                    "charging_power"        => $currentChargingPower, 
                    "tariffs_power_range"   => $chargingPrice -> min_kwt    . ' - ' . $chargingPrice -> max_kwt,
                    "tariffs_daytime_range" => $chargingPrice -> start_time . ' - ' . $chargingPrice -> end_time,  
                    "tariff_price"          => $chargingPrice -> price,   
                    "start_at"              => now() -> timestamp,       
                    "end_at"                => null,
                ]
            );
    }

    /**
     * Set last charging power record end 
     * timestamp with current time if not
     * set already.
     * 
     * @return void
     */
    public function stampLastChargingPowerRecord()
    {
        $lastRecord = $this 
            -> charging_powers()
            -> where( 'order_id', $this -> id )
            -> orderBy( 'id', 'desc' )
            -> first();
        
        if( $lastRecord -> end_at === null )
        {
            $lastRecord -> end_at = now() -> timestamp;
            $lastRecord -> save();
        }
    }

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

        if( $chargingStatus == OrderStatusEnum :: USED_UP || $chargingStatus == OrderStatusEnum :: CHARGED )
        {
            if($this -> getCharger() -> isPenaltyEnabled()) 
            {
                User :: sendSms($this -> user -> phone_number, $this -> onPenaltyMessage());
            }
        }
        else if( $chargingStatus == OrderStatusEnum :: FINISHED )
        {
            User :: sendSms($this -> user -> phone_number, $this -> chargingCompleteMessage());
        }

        $shouldChargingPowerRecordBeStamped = in_array(
            $chargingStatus, 
            [
                OrderStatusEnum :: FINISHED, 
                OrderStatusEnum :: USED_UP, 
                OrderStatusEnum :: CHARGED
            ],
        );

        if( $shouldChargingPowerRecordBeStamped )
        {
            $this -> stampLastChargingPowerRecord();
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
        if(! Helper :: isDev())
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
     * Determine if charging is stopped 
     * due to that the car is charged or ether
     * user has used up the money and is in penalty
     * relief mode.
     * 
     * @return bool
     */
    public function enteredPenaltyReliefMode()
    {
        $enteredPenaltyReliefModeTimestamp = Timestamp :: build( $this ) -> getStopChargingTimestamp();
        return !! $enteredPenaltyReliefModeTimestamp;     
    }

    /**
     * Determine if user already used up all the 
     * money he/she typed when charging with BY_AMOUNT.
     * 
     * @return bool
     */
    public function hasAlreadyUsedUpMoney()
    {
        return !! Timestamp :: build( $this ) -> getChargingStatusTimestamp( OrderStatusEnum :: USED_UP );
    }

    /**
     * Determine if user is on penalty.
     * 
     * @return bool
     */
    public function isOnPenalty()
    {
        return !! Timestamp :: build( $this ) -> getPenaltyTimestamp();
    }

    /**
     * Determine if charging has officially started.
     * 
     * @param   float $kiloWattHour
     * @return  bool
     */
    public function chargingHasStarted()
    {
        $chargingPower    = $this  -> getChargingPower();
        $kiloWattHourLine = $this  -> kiloWattHourLine;

        return $chargingPower > $kiloWattHourLine;
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
    public function shouldGoToPenalty()
    {
        if( $this -> charger_connector_type -> isChargerFast() )
        {
            return false;
        }

        if( ! $this -> carHasAlreadyStoppedCharging() )
        {
            return false;
        }

        if( ! $this -> getCharger() -> penalty_enabled ) 
        {
            return false;
        }

        $config               = Config :: first();
        $penaltyReliefMinutes = $config -> penalty_relief_minutes;

        $chargedTime = Timestamp :: build( $this ) -> getStopChargingTimestamp();

        if( ! $chargedTime )
        {
            return false;
        }

        $elapsedTime         = $chargedTime -> diffInMinutes( now() );

        return $elapsedTime >= $penaltyReliefMinutes;
    }

    /**
     * Determine if car has already stopped charging.
     * 
     * @return bool
     */
    private function carHasAlreadyStoppedCharging()
    {
        return in_array( $this -> charging_status, [ OrderStatusEnum :: CHARGED, OrderStatusEnum :: USED_UP ]);
    }

    /**
     * Determine if order can go to finish status.
     * 
     * @param string|null
     * @return bool
     */
    public function canGoToFinishStatus()
    {
        $finishableStatuses = [
        OrderStatusEnum :: INITIATED ,
        OrderStatusEnum :: CHARGING  ,
        OrderStatusEnum :: CHARGED   ,
        OrderStatusEnum :: USED_UP   ,
        OrderStatusEnum :: ON_FINE   ,
        OrderStatusEnum :: ON_HOLD   ,
        ];

        return in_array( $this -> charging_status, $finishableStatuses );
    }

    /**
     * Determine if consumed money is above 
     * the paid currency.
     * 
     * @return bool
     */
    public function shouldPay()
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
    public function shouldRefund()
    {
        $paidMoney      = $this -> countPaidMoney();
        $consumedMoney  = $this -> countConsumedMoney();
        
        return  $consumedMoney < $paidMoney;
    }

    /**
     * Count money the user has already paid.
     * 
     * @return  float
     * @example 10.25
     */
    public function countPaidMoney()
    {
        if( count( $this -> payments ) == 0 )
        {
            return 0.0;
        }
    
        $paidMoney = $this 
            -> payments 
            -> where( 'type', PaymentTypeEnum :: CUT ) 
            -> sum( 'price' );

        $paidMoney = round( $paidMoney, 2 );
        
        return $paidMoney;
    }

    /**
     * Count the money user has already consumed(Charged).
     * 
     * @return float
     */
    public function countConsumedMoney()
    {
        if( $this -> hasAlreadyUsedUpMoney() )
        {
            return $this -> target_price;
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
        $timestamp           = Timestamp :: build( $this );
        $elapsedMinutes      = $timestamp -> calculateChargingElapsedTimeInMinutes();

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
        $consumedMoney = 0;

        $chargingPriceRanges -> each( function ( $chargingPriceInstance ) use ( &$consumedMoney, $elapsedMinutes ) {     
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
        // $timestamp          = Timestamp :: build( $this );
        // $elapsedMinutes     = $timestamp -> calculateChargingElapsedTimeInMinutes();
        // $chargingPrice      = $this -> getCurrentChargingPrice();
        
        // return $chargingPrice * $elapsedMinutes;

        if( $this -> charging_powers === null )
        {
            return 0;
        }

        $chargingPricesSum = $this 
            -> charging_powers
            -> reduce(function($carry, $chargingPower) {
                return $carry + $chargingPower -> getIntervalPrice();
            });

        return $chargingPricesSum;
    }

    /**
     * Get current charging price.
     * 
     * #### Deprecated
     * 
     * @return float
     */
    private function getCurrentChargingPrice()
    {
        $timestamp          = Timestamp :: build( $this );
        $chargingPower      = $this -> kilowatt -> getChargingPower();
        $startChargingTime  = $timestamp -> getChargingStatusTimestamp( OrderStatusEnum :: CHARGING );

        if( ! $startChargingTime )
        {
            return null;
        }

        $chargingPriceInfo  = $this 
        -> charger_connector_type 
        -> getSpecificChargingPrice( $chargingPower, $startChargingTime  -> toTimeString() );
        
        if( ! $chargingPriceInfo )
        {
            throw new NoSuchChargingPriceException();
        }

        return $chargingPriceInfo -> price;
    }

    /**
     * Get charging price according to charging 
     * power and current time.
     * 
     * @return ChargingPrice
     */
    private function getChargingPrice($chargingPower)
    {
        $currentTime  = now() -> toTimeString();

        $chargingPriceInfo  = $this 
        -> charger_connector_type 
        -> getSpecificChargingPrice( $chargingPower, $currentTime);
        
        if( ! $chargingPriceInfo )
        {
            throw new NoSuchChargingPriceException();
        }

        return $chargingPriceInfo;
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
        if( $this -> charger_connector_type -> isChargerFast() )
        {
            return null;
        }

        $timestamp              = Timestamp :: build( $this );
        $penaltyTimeInMinutes   = $timestamp -> penaltyTimeInMinutes();
        $penaltyPricePerMinute  = Helper :: getPenaltyPricePerMinute();
                
        return $penaltyTimeInMinutes * $penaltyPricePerMinute;    
    }
}
