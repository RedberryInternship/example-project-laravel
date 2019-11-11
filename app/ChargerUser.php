<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChargerUser extends Model
{
    protected $fillable = [
        'charger_id',
        'user_id'
    ];


    public function charger()
    {
    	return $this -> belongsTo('App\Charger');
    }

    public function user()
    {
    	return $this -> belongsTo('App\User');
    }
}
