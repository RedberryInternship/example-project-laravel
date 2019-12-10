<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserCarModel extends Model
{
    protected $fillable = [
        'user_id',
        'model_id'
    ];

    public function users()
    {
        return $this -> belongsTo('App/User','user_car_models');
    }

    public function car_model()
    {
    	return $this -> belongsTo('App\CarModel');
    }
}
