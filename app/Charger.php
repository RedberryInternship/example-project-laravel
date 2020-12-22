<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use App\Facades\Charger as MishasCharger;
use Illuminate\Database\Eloquent\Builder;
use App\Enums\ChargerType as ChargerTypeEnum;

class Charger extends Model
{
    use HasTranslations;

    /**
     * Attribute that tells the model
     * which fields should be translatable.
     * 
     * @var array
     */
    public $translatable = [
      'description', 
      'location',
      'name'
    ];

    /**
     * Laravel guarded attribute
     * for mass assignment protection.
     * 
     * @var array
     */
    protected $guarded = [];

    /**
     * Laravel casts attribute.
     * 
     * @var array
     */
    protected $casts = [
        'charger_id'  => 'int',
        'name'        => 'array',
        'public'      => 'int',
        'active'      => 'int',
        'is_paid'     => 'int',
    ];

    /**
     * Relation to companies.
     * 
     * @return Company
     */
    public function company()
   	{
   		return $this -> belongsTo(Company::class);
   	}

    /**
     * Relation with tags.
     * 
     * @return Collection
     */
    public function tags()
    {
        return $this -> belongsToMany('App\Tag', 'charger_tags');
    }

    /**
     * Relation with connector types
     * with active connector types filtered.
     * 
     * @return Collection
     */
    public function connector_types()
    {
        return $this
                    -> connector_types_all()
                    -> where('status','active');
    }

    /**
     * Relation with connector types
     * without filter.
     * 
     * @return Collection
     */
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
     * Relationship with charger connector types.
     * 
     * @return Collection
     */
    public function charger_connector_types()
    {
        return $this -> hasMany(ChargerConnectorType::class);
    }

    /**
     * Relation with business services.
     * 
     * @return Collection
     */
    public function business_services()
    {
        return $this -> belongsToMany(BusinessService :: class, 'charger_business_services');
    }

    /**
     * Relation with groups.
     * 
     * @return Collection
     */
    public function groups()
    {
        return $this -> belongsToMany(Group::class);
    }

    /**
     * Relation to whitelist.
     * 
     * @return Collection
     */
    public function whitelist()
    {
        return $this -> hasMany(Whitelist :: class);
    }

    /**
     * Retrieve charger's orders.
     * 
     * @return Collection
     */
    public function orders()
    {
        $chargerConnectorTypeIds = $this -> charger_connector_types -> map(function( $cct ) {
            return $cct -> id;
        }) -> toArray();

        return Order :: whereIn( 'charger_connector_type_id', $chargerConnectorTypeIds ) -> get();
    }

    /**
     * Set query relations.
     * 
     * @param Builder
     * @return Builder
     */
    public function scopeWithAllAttributes($query)
    {
        return $query -> with(
            [
                'business_services',
                'connector_types',
                'whitelist',
                'company',
            ]
        );
    }

    /**
     * Determine if penalty is enabled.
     * 
     * @return boolean
     */
    public function isPenaltyEnabled(): bool
    {
        return $this -> penalty_enabled;
    }

    /**
     * Determine if charging on this 
     * charger is paid.
     * 
     * @return boolean
     */
    public function isPaid(): bool
    {
        return $this -> is_paid;
    }

    /**
     * Add is favorite attribute to
     * chargers collection.
     * 
     * @param Collection $chargers
     * @return void
     */
    public static function addIsFavoriteAttributes(&$chargers)
    { 
        $user = auth('api') -> user();

        if ($user)
        {
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
    }

    /**
     * Add charging prices to chargers collection.
     * 
     * @param Collection $chargers
     * @return void
     */
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

    /**
     * Hide chargers from non whitelisted users.
     * 
     * @param  Charger $charger
     * @return Collection
     */
    public static function filterChargersForNotWhitelistedUsers( $chargers ) 
    {
        $user = auth('api') -> user();

        return $chargers -> filter(function( $charger ) use( $user ) {

            /**
             * if charger is not hidden show it to the user.
             */
            if(! $charger -> hidden) 
            {
                return true;
            }

            /**
             * If user is not authenticated and 
             * charger is hidden.
             */
            if(! $user )
            {
                return false;
            }

            /**
             * If user is authenticated and charger is hidden
             * with user's phone_number in its whitelist, 
             * in that case show charger.
             */
            foreach( $charger -> whitelist as $allowedMember )
            {
                if($allowedMember -> phone === $user -> phone_number) 
                {
                    return true;
                }
            }

            /**
             * if user is authenticated and his/her phone number is not
             * in charger's whitelist, then hide charger from that user.
             */
            return false;
        });
    }

    public static function addIsFreeAttributes(&$chargers)
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

    /**
     * Get charger ids with appropriate types.
     * 
     * @return array
     */
    public static function types(): array
    {
        $chargers = self :: with( 'charger_connector_types' ) -> get() 
        -> map( function( $charger )
        {
            return [
            'id' => $charger -> id,
            'type' => $charger -> charger_connector_types -> first() -> determineChargerType()
            ];
        }) -> toArray();

        $chargerTypes = [];
        foreach( $chargers as $charger )
        {
        $chargerTypes[ $charger[ 'id' ] ] = $charger[ 'type' ];
        }

        return $chargerTypes;
    }

    /**
     * get lvl 2 charger ids.
     * 
     * @return array
     */
    public static function getLvl2Ids(): array
    {
        $ids = [];

        foreach( self :: types() as $key => $val )
        {
            if( $val == ChargerTypeEnum :: LVL2 )
            {
                $ids []= $key;
            }
        }

        return $ids;
    }
    
    /**
     * get fast charger ids.
     * 
     * @return array
     */
    public static function getFastIds(): array
    {
        $ids = [];

        foreach( self :: types() as $key => $val )
        {
            if( $val == ChargerTypeEnum :: FAST )
            {
                $ids []= $key;
            }
        }

        return $ids;
    }
}
