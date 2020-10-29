<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Traits\Message;
use App\Library\Entities\Helper;
use App\Enums\OrderStatus as OrderStatusEnum;
use App\Library\Entities\Order as OrderEntity;
use App\Library\Entities\ChargingProcess\Hook;
use App\Library\Entities\ChargingProcess\State;
use App\Library\Entities\ChargingProcess\Calculator;

class Order extends Model
{
    use State;
    use Message;
    use Calculator;
    use OrderEntity;

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
     * @return Builder
     */
    public function scopeWithoutOld( $query )
    {
        return $query -> where('id', '>', 9664);
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
            Helper :: isDev() && User :: sendSms($this -> user -> phone_number, $this -> onPenaltyMessage());
        }
        else if( $chargingStatus == OrderStatusEnum :: FINISHED )
        {
            User :: sendSms($this -> user -> phone_number, $this -> chargingCompleteMessage());
        }
    }
}
