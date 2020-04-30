<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\ChargerConnectorType;
use App\ChargingType;
use Carbon\Carbon;
use App\Payment;
use App\User;

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
    public function createKilowatt($consumed)
    {
        $this -> kilowatt()
            -> create([
                'consumed' => [
                    [
                        'date' => Carbon::now(),
                        'value' => $consumed,
                    ]
                ],
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
