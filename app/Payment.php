<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $guarded = [];

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

    public function scopeWithUserCards($query)
    {
        return $query -> with('user_card');
    }
}
