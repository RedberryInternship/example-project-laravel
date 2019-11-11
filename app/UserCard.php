<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserCard extends Model
{
    protected $fillable = [
        'old_id',
        'user_old_id',
        'user_id',
        'masked_pan',
        'order_index',
        'transaction_id',
        'card_holder',
        'default',
        'active'
    ];

    public function user()
    {
        return $this -> belongsTo('App\User');
    }
}
