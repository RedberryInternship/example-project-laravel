<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'old_id',
        'order_id',
        'status',
        'active',
        'confirmed',
        'confirm_date',
        'date',
        'price',
        'prrn',
        'trx_id',
        'user_card_id'
    ];

    public function order()
    {
    	return $this -> belongsTo('App\Order');
    }

    public function user_card()
    {
    	return $this -> belongsTo('App\UserCard');
    }

    public function scopeConfirmed($query)
    {
        return $query -> where('confirmed', 1);
    }
}
