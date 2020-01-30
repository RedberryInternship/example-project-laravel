<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Charger extends Model
{
    use HasTranslations;

    public $translatable = [
      'description', 
      'location',
      'name'
    ];

    protected $fillable = [
        'name',
        'charger_id',
        'code',
        'description',
        'user_id',
        'location',
        'public',
        'active',
        'lat',
        'lng',
        'old_id',
        'charger_group_id',
        'iban',
        'last_update'
    ];

    protected $casts = [
      'name' => 'array'
    ];

    public function user()
   	{
   		return $this -> belongsTo('App\User');
   	}

    public function tags()
    {
        return $this -> belongsToMany('App\Tag', 'charger_tags');
    }

    public function connector_types()
    {
        return $this -> belongsToMany('App\ConnectorType', 'charger_connector_types') -> withPivot('charger_type_id');
    }

    public function charger_types()
    {
      return $this -> belongsToMany('App\ChargerType', 'charger_connector_types') -> withPivot('charger_type_id');
    }
    
    public function charging_prices()
    {
        return $this -> hasMany('App\ChargingPrice');
    }
    public function fast_charging_prices()
    {
        return $this -> hasMany('App\FastChargingPrice');
    }
}
