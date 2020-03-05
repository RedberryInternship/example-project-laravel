<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChargerGroup extends Model
{
    protected $fillable = [
        'name',
        'user_id'
    ];

    public function user()
    {
        return $this -> belongsTo('App\User');
    }

    public function chargers()
    {
    	return $this -> hasMany('App\Charger');
    }

    public function scopeWithChargers($query)
    {
        return $query -> with(['chargers' => function($q) {
            return $q -> withAllAttributes();
        }]);
    }
}
