<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mark extends Model
{
    public function models()
    {
        return $this -> hasMany('App\CarModel');
    }

    public function scopeWithModelsOrNone($query)
    {
    	$query -> has('models');
    	$query -> with(['models' => function($q){
    		$q -> select('name', 'id', 'mark_id');
    	}]);
    }
}
