<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CarModel extends Model
{
   	public function mark()
    {
    	return $this -> belongsTo('App\Mark');
    }
    public function users()
    {
    	return $this -> belongsToMany('App\User', 'user_car_models','model_id','user_id');
    }
}
