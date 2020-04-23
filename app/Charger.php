<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use App\Facades\Charger as MishasCharger;
use App\ChargerTransaction;

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
        return $this
                    -> connector_types_all()
                    -> where('status','active');
    }

    public function connector_types_all()
    {
        return $this
                    -> belongsToMany('App\ConnectorType', 'charger_connector_types')
                    -> withPivot([
                        'id',
                        'min_price',
                        'max_price',
                        'status',
                        'm_connector_type_id',
                    ]);
    }

    public function charger_group()
    {
        return $this -> belongsTo('App\ChargerGroup');
    }

    public function scopeFilterBy($query, $param, $value)
    {
        if (isset($param) && $param && isset($value) && $value)
        {
            if (is_array($value))
            {
                if ($value[0])
                {
                    $query = $query -> whereIn($param, $value);
                }
            }
            else
            {
                $query = $query -> where($param, $value);
            }
        }

        return $query;
    }

    public function orders()
    {
        return $this -> hasMany('App\Order');
    }
    public function business_services()
    {
        return $this -> belongsToMany('App\BusinessService', 'charger_business_services');
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
            $connectorTypeNames = ['Type 2'];
        }
        else if ($type == 'fast')
        {
            $connectorTypeNames = ['Combo 2', 'CHadeMO'];
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

    public function scopeGroupedChargersWithSiblingChargers($query)
    {
        return $query -> with(['charger_group' => function($q) {
            return $q -> withChargers();
        }]);
    }

    public function scopeWithAllAttributes($query)
    {
        return $query -> with([
            'tags',
            'connector_types.charging_prices',
            'connector_types.fast_charging_prices',
            'business_services'
        ]);
    }

    public function addFilterAttributeToChargers(&$chargers, $favoriteChargers, $inner = false)
    {
        foreach ($chargers as &$charger)
        {
            $isFavorite = false;
            if (in_array($charger -> id, $favoriteChargers))
            {
                $isFavorite = true;
            }

            $charger -> is_favorite = $isFavorite;

            if ( ! $inner && isset($charger -> charger_group) && isset($charger -> charger_group -> chargers) && ! empty($charger -> charger_group -> chargers))
            {
                $this -> addFilterAttributeToChargers($charger -> charger_group -> chargers, $favoriteChargers, true);
            }
        }
    }

    public static function addIsFreeAttributeToChargers(&$chargers, $inner = false)
    {
        /**
         * get free_charger_ids from our db
         * 
         * $free_charger_ids = ChargerTransaction::getFreeChargersIds();
         */

        $free_charger_ids = MishasCharger::getFreeChargersIds();

        foreach ($chargers as &$charger)
        {
            $isFree = false;
            if (in_array($charger -> charger_id, $free_charger_ids))
            {
                $isFree = true;
            }

            $charger -> is_free = $isFree;
            
            $is_it_parent_charger = ! $inner 
                && isset($charger -> charger_group) 
                && isset($charger -> charger_group -> chargers) 
                && ! empty($charger -> charger_group -> chargers);
            
            if ($is_it_parent_charger)
            {
                static::addIsFreeAttributeToChargers(
                    $charger -> charger_group -> chargers, 
                    $free_charger_ids, 
                    true,
                );
            }
        }
    }

    public static function addIsFreeAttributeToCharger(&$charger)
    {
        /**
         * set is free attribute for charger from out db
         * 
         * $charger -> is_free = ChargerTransaction::isChargerFree( $charger -> charger_id );
         */
        
        $charger -> is_free = MishasCharger::isChargerFree( $charger -> charger_id );
    }
}

