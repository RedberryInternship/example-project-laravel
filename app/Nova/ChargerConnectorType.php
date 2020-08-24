<?php

namespace App\Nova;

use App\ConnectorType;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\BelongsTo;
use App\ChargerConnectorType as CCT;
use Laravel\Nova\Http\Requests\NovaRequest;

class ChargerConnectorType extends Resource
{

    public static $displayInNavigation = false;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = CCT :: class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'title';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        $isFast = null;

        if( $this -> id )
        {
            $isFast = CCT :: find( $this -> id ) -> isChargerFast();
        }
        
        return [
            ID::make()->sortable(),
            Text::make('Connector Type', 'connector_type_id', function( $value ) {
                return ConnectorType :: where( 'id', $value ) -> first() -> name;
            }) -> readonly(),
            HasMany::make('Fast Charging Prices') -> canSee( function() use( $isFast ){
                
                if( is_null( $isFast ) )
                {
                    return true;
                }

                return $isFast;
            }),
            HasMany::make('Charging Prices') -> canSee( function() use( $isFast ) {

                if( is_null( $isFast ) )
                {
                    return true;
                }
                
                return ! $isFast;
            }),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }

    /**
     * Index query for charger connector types.
     */
    public static function indexQuery(NovaRequest $request, $query)
    {
        $query -> where( 'status', 'active' );
    }
}
