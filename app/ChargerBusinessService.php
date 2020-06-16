<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class ChargerBusinessService extends Model
{
    protected $fillable = [
        'charger_id',
        'business_service_id'
    ];

    public function business_services()
    {
    	return $this -> belongsTo('App\BusinessService');
    }
    
    public function chargers()
    {
        return $this -> belongsTo('App/Charger','charger_tags');
    }
}
