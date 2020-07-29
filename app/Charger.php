<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use App\Facades\Charger as MishasCharger;
use Illuminate\Database\Eloquent\Builder;

class Charger extends Model
{
    use HasTranslations;

    public $translatable = [
      'description', 
      'location',
      'name'
    ];

    protected $guarded = [];

    protected $casts = [
      'name' => 'array',
      'charger_id' => 'int',
    ];

    public function company()
   	{
   		return $this -> belongsTo(Company::class);
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

    /**
     * Charger hasMany relationship with ChargerConnectorType.
     * 
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function charger_connector_types()
    {
        return $this -> hasMany(ChargerConnectorType::class);
    }

    public function groups()
    {
        return $this -> belongsToMany(Group::class);
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
                -> has('groups')
                -> with(['groups' => function($q) {
                    return $q -> withChargers();
                }]);
    }

    public function scopeFilterNotGroupedChargers($query)
    {
        return $query -> doesntHave('groups');
    }

    public function scopeGroupedChargersWithSiblingChargers($query)
    {
        return $query -> with(['groups' => function($q) {
            return $q -> withChargers();
        }]);
    }

    public function scopeWithAllAttributes($query)
    {
        return $query -> with([
            'tags',
            'connector_types',
            'business_services'
        ]);
    }

    public static function addFilterAttributeToChargers(&$chargers)
    { 
        $user = auth('api') -> user();

        $favoriteChargers = $user -> favorites -> pluck('id') -> toArray();

        foreach ($chargers as &$charger)
        {
            $isFavorite = false;
            if (in_array($charger -> id, $favoriteChargers))
            {
                $isFavorite = true;
            }

            $charger -> is_favorite = $isFavorite;
        }
    }

    public static function addChargingPrices(&$chargers)
    {
        $chargingPrices     = ChargingPrice::all() -> groupBy('charger_connector_type_id');
        $fastChargingPrices = FastChargingPrice::all() -> groupBy('charger_connector_type_id');

        foreach ($chargers as &$charger)
        {
            foreach ($charger -> connector_types as &$connectorType)
            {
                $connectorType -> charging_prices      = [];
                $connectorType -> fast_charging_prices = [];
                if (isset($chargingPrices[$connectorType -> pivot -> id]))
                {
                    $connectorType -> charging_prices = $chargingPrices[$connectorType -> pivot -> id] -> toArray();
                }

                if (isset($fastChargingPrices[$connectorType -> pivot -> id]))
                {
                    $connectorType -> fast_charging_prices = $fastChargingPrices[$connectorType -> pivot -> id] -> toArray();
                }
            }
        }
    }

    public static function addIsFreeAttributeToChargers(&$chargers)
    {
        /**
         * get free_charger_ids from our db.
         * 
         * $free_charger_ids = Charger::getFreeChargersIds();
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
        }
    }

    public static function addIsFreeAttributeToCharger(&$charger)
    {
        /**
         * set is free attribute for charger from out db.
         * 
         * $charger -> is_free = Charger::isChargerFree( $charger -> charger_id );
         */
        
        $charger -> is_free = MishasCharger::isChargerFree( $charger -> charger_id );
    }

    /** 
     * Get free chargers IDs.
     * 
     * @return array
     */
    public static function getFreeChargersIds()
    {
        $freeChargersIds = Charger :: with('charger_connector_types.orders')
            -> whereDoesntHave( 'charger_connector_types.orders' )
            -> orWhere( function ( Builder $builder ){
                return $builder 
                    -> whereDoesntHave('charger_connector_types.orders', function( Builder $builder){
                        return $builder
                            -> where( 'charging_status', '!=' , 'FINISHED' );
                    });
            })
            -> pluck('id')
            -> toArray();

       return array_values(
           array_unique( $freeChargersIds )
       );
    }

    /**
      * Is the charger free.
      *
      * @return bool
      */
      public static function isChargerFree($id)
      {
          return in_array($id, static::getFreeChargersIds());
      }
    
    public function hasChargingConnector($type, $chargerConnectorTypes)
    {
        $connectorTypes = $type == 'fast' ? ['combo 2', 'chademo'] : ['type 2'];   

        $hasChargingConnector = false;
        foreach ($chargerConnectorTypes as $chargerConnectorType)
        {
            $connectorName = $chargerConnectorType -> connector_type -> name;

            if (in_array(strToLower($connectorName), $connectorTypes))
            {
                $hasChargingConnector = true;

                break;
            }
        }

        return $hasChargingConnector;
    }

    public function chargerConnectorTypeOrders()
    {
        return $this -> hasManyThrough(Order::class, ChargerConnectorType::class);
    }
}
