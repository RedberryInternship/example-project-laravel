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

    // public $casts = [
    //     'extra_attributes' => 'array',
    // ];

    // public function getExtraAttributesAttribute(): SchemalessAttributes
    // {
    //     return SchemalessAttributes::createForModel($this, 'extra_attributes');
    // }

    // public function scopeWithExtraAttributes(): Builder
    // {
    //     return SchemalessAttributes::scopeWithSchemalessAttributes('extra_attributes');
    // }

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

    // public function prices()
    // {
    //     $charger_types = $this -> charger_types;
    //     foreach($charger_types as $charger_type)
    //     {
    //         if($charger_type -> id == 1)
    //         {
    //             return $this -> hasMany('App\ChargingPrice');
    //         }elseif($charger_type -> id == 2)
    //         {
    //             return $this -> hasMany('App\FastChargingPrice');
    //         }
    //     }
    // }
    public function charging_prices()
    {
        return $this -> hasMany('App\ChargingPrice');
    }
    public function fast_charging_prices()
    {
        return $this -> hasMany('App\FastChargingPrice');
    }
}
