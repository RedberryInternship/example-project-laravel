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
      'name' => 'array',
      'charger_id' => 'int',
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

    public function charger_group()
    {
        return $this -> belongsTo('App\ChargerGroup');
    }

    public function orders()
    {
        return $this -> hasMany('App\Order');
    }

    public function scopeActive($query)
    {
        return $query -> where('active', 1);
    }

    public function scopeFilterByFreeOrNot($query, $free)
    {
        return $query;
    }

    public function scopeFilterByType($query, $type)
    {
        $connectorTypeNames = [];
        if ($type == 'level2')
        {
            $connectorTypeNames = ['Type 2', 'Combo 2'];
        }
        else if ($type == 'fast')
        {
            $connectorTypeNames = ['CHadeMO'];
        }

        if (empty($connectorTypeNames))
        {
            return $query;
        }


        return $query -> whereHas('connector_types', function($query) use($connectorTypeNames) {
            return $query -> whereIn('connector_types.name', $connectorTypeNames);
        });
    }

    public function scopeFilterByPublicOrNot($query, $public)
    {
        return $query -> where('public', $public);
    }

    public function scopeFilterByBusiness($query, $businessID)
    {
        return $query -> where('user_id', $businessID);
    }

    public function scopeFilterByText($query, $text)
    {
        return $query -> where(function($q) use ($text) {
            return $q
                -> where('location->en', 'like', '%' . $text . '%')
                -> orWhere('location->ka', 'like', '%' . $text . '%')
                -> orWhere('location->ru', 'like', '%' . $text . '%');
        });
    }

    public function scopeFilterGroupedChargers($query)
    {
        return
            $query
                -> has('charger_group')
                -> with(['charger_group' => function($q) {
                    return $q -> withChargers();
                }]);
    }

    public function scopeFilterNotGroupedChargers($query)
    {
        return $query -> doesntHave('charger_group');
    }

    public function scopeGroupedChargersWithSibblingChargers($query)
    {
        return $query -> with(['charger_group' => function($q) {
            return $q -> withChargers();
        }]);
    }

    public function scopeWithAllAttributes($query)
    {
        return $query -> with([
            'tags',
            'connector_types',
            'charger_types',
            'charging_prices',
            'fast_charging_prices'
        ]);
    }
}
