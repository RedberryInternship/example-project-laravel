<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'order_id',
        'type',
        'price',
        'transaction_id',
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
}
