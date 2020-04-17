<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Interfaces\Charger;	

class Order extends Model
{
    protected $fillable = [
        'old_id',
        'charger_id',
        'user_id',
        'connector_type_id',
        'charger_type_id',
        'charging_type_id',
        'finished',
        'charge_fee',
        'charge_time',
        'charger_transaction_id',
        'price',
        'target_price',
        'confirmed',
        'confirm_date',
        'requested_already',
        'comment',
        'status',
    ];


    public function user()
    {
    	return $this -> belongsTo('App\User');
    }

    public function charger()
    {
    	return $this -> belongsTo('App\Charger');
    }

    public function connector_type()
    {
    	return $this -> belongsTo('App\ConnectorType');
    } 

    public function charging_type()
    {
    	return $this -> belongsTo('App\ChargingType');
    }

    public function payments()
    {
        return $this -> hasMany('App\Payment');
    }

    public function scopeConfirmed($query)
    {
        return $query -> where('confirmed', 1);
    }

    public function scopeConfirmedPayments($query)
    {
        return $query -> with(['payments' => function($q) {
            return $q -> confirmed();
        }]);
    }

    public function scopeConfirmedPaymentsWithUserCards($query)
    {
        return $query -> with(['payments' => function($q) {
            return $q -> confirmed() -> withUserCards();
        }]);
    }
}
