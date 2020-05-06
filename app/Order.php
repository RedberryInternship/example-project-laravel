<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Exceptions\NoSuchFastChargingPriceException;
use App\Exceptions\NoSuchChargingPriceException;

use App\Enums\PaymentType as PaymentTypeEnum;
use App\Enums\ChargerType as ChargerTypeEnum;
use App\Enums\OrderStatus as OrderStatusEnum;

use Carbon\Carbon;

use App\Facades\Charger as MishasCharger;

class Order extends Model
{
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

    /**
     * override model boot to add hooks.
     * 
     * @return void
     */
    public static function boot()
    {
        parent :: boot();

        /** 
         * Set charging status change dates initial value 
         * when creating.
         */
        static :: creating( function ( $model ) {

            $availableOrderStatuses = OrderStatusEnum :: getConstantsValues();
            $initialStatuses        = [];

            foreach( $availableOrderStatuses as $status )
            {
                $initialStatuses [ $status ] = null;
            }

            if( $model -> charging_status == OrderStatusEnum :: INITIATED )
            {
                $initialStatuses [ OrderStatusEnum :: INITIATED ] = now();
            }
            else
            {
                $initialStatuses [ OrderStatusEnum :: CHARGING ]  = now();
            }

            $model -> charging_status_change_dates = $initialStatuses;
        });

        /**
         * Set charging status change dates if not set,
         * when updating.
         */
        static :: updating( function ( $model ) {
            $chargingStatus = $model -> charging_status;
            $orderChargingStatusChargeDates = $model -> charging_status_change_dates; 

            if( ! $orderChargingStatusChargeDates [ $chargingStatus ] )
            {
                $orderChargingStatusChargeDates [ $chargingStatus ] = now();
                $model -> charging_status_change_dates = $orderChargingStatusChargeDates;
            }
        });
    }

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
     * Order belongsTo relationship with ChargingType.
     * 
     * @return App\ChargingType
     */
    public function charging_type()
    {
    	return $this -> belongsTo( ChargingType :: class );
    }

    /**
     * Order hasMany relationship with Payment.
     * 
     * @return Illuminate\Support\Collection
     */
    public function payments()
    {
        return $this -> hasMany( Payment :: class );
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
        $consumedWatts      = $this -> getLatestConsumedKilowatt() -> value;
        $consumedKilowatts  = $this -> convertWattsIntoKilowatts( $consumedWatts );
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
     * Convert Wats into kiloWatts.
     * 
     * @param float|integer $watts
     */
    private function convertWattsIntoKilowatts( $watts )
    {
        return $watts / 1000;
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

    /**
     * Helper to create new kilowatt record.
     * 
     * @param int|float $consumed
     * @return void
     */
    public function createKilowatt($consumed, $chargingPower = 0 )
    {
        $this -> kilowatt()
            -> create([
                'consumed'      => [
                    [
                        'date' => Carbon::now(),
                        'value' => $consumed,
                    ]
                ],
                'charging_power' => $chargingPower,
            ]);
    }

    /**
     * Helper to add new kilowatt updated values
     * into kilowatt consumed record as json.
     * 
     * @param int|float $value
     * @return void
     */
    public function addKilowatt( $value )
    { 
        $this -> load( 'kilowatt' );

        if( ! $this -> kilowatt )
        {
            $this -> createKilowatt( $value );
        }
        else
        {
            $consumed_kilowatt_data = $this -> kilowatt -> consumed;

            $updated_data = array_merge( $consumed_kilowatt_data, [
                [
                    'date' => Carbon::now(),
                    'value' => $value,
                ],
            ]);

            $this -> kilowatt() -> update([
                'consumed' => $updated_data,
            ]);

            $this -> kilowatt -> refresh();
        }
    }

    /**
     * Get all consumed kilowatt data.
     * 
     * @return Illuminate\Http\Collection
     */
    public function consumedKilowatts()
    {
        $consumed_kilowatts     = $this -> load('kilowatt') 
                                        -> kilowatt 
                                        -> consumed;
        return collect($consumed_kilowatts);
    }

    /**
     * Get latest consumed kilowatt.
     * 
     * @return object
     */
    public function getLatestConsumedKilowatt()
    {   
        return $this -> consumedKilowatts() -> last();
    }

    /**
     * Get confirmed orders - scope.
     * 
     * @param Illuminate\Database\Eloquent\Builder
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeConfirmed($query)
    {
        return $query -> where('confirmed', 1);
    }

    /**
     * Get orders with confirmed payments - scope.
     * 
     * @param Illuminate\Database\Eloquent\Builder
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeConfirmedPayments($query)
    {
        return $query -> with(['payments' => function($q) {
            return $q -> confirmed();
        }]);
    }

    /**
     * Get orders with confirmed payments(with user cards) - scope.
     * 
     * @param Illuminate\Database\Eloquent\Builder
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeConfirmedPaymentsWithUserCards($query)
    {
        return $query -> with(['payments' => function($q) {
            return $q -> confirmed() -> withUserCards();
        }]);
    }
}
