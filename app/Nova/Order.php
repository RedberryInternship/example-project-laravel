<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\HasMany;
use App\Nova\Actions\ExportOrders;
use App\Nova\Filters\EndDateRange;
use App\Nova\Filters\StartDateRange;
use App\Nova\Filters\Order\OrderType;
use App\Nova\Filters\Order\ChargerType;
use App\Nova\Filters\Order\ChargingType;
use Titasgailius\SearchRelations\SearchesRelations;

class Order extends Resource
{
    use SearchesRelations;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Order';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * Override Resource Title.
     */
    public function title()
    {
        return
            $this -> user -> first_name . ' ' .
            $this -> user -> last_name . ' - ' .
            $this -> id;
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'charger_transaction_id',
    ];

    /**
     * The relationship columns that should be searched.
     *
     * @var array
     */
    public static $searchRelations = [
        'user' => ['first_name', 'last_name', 'email']
    ];

    /**
     * Grouping nova resource.
     */
    public static $group = 'User Resources';

    /**
     * Eager Loading.
     *
     * @var string
     */
    public static $with = [
        'user.role',
        'charger_connector_type.charger',
        'charger_connector_type.connector_type'
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        $isLVL2 = ! $this -> charger_connector_type -> isChargerFast();

        return [
            ID::make()
                ->sortable(),
            
            BelongsTo::make('User'),
            
            HasMany::make('Payments'),

            HasMany::make('Charging Powers') -> canSee(function() use( $isLVL2 ) {
                return $isLVL2;
            }),

            BelongsTo::make('Charger Connector Type')
                -> displayUsing(function($chargerConnectorType) {
                    return $chargerConnectorType -> charger -> name . ' - ' . $chargerConnectorType -> connector_type -> name;
                }) -> onlyOnDetail(),

            Text::make('Charging Status')
                ->readonly(),

            Text::make('Charger Transaction Id') -> onlyOnDetail(),

            Text::make('Charger Name') -> onlyOnDetail(),

            Text::make('Charge Price'),

            Text::make('Penalty Fee'),

            Text::make('Duration'),
            
            Text::make('Charge Power'),

            Text::make('Consumed Kilowatts'),

            Text::make('Address') -> onlyOnDetail(),

            Text::make('Start Date'),

            Text::make('Comment') -> onlyOnDetail(),

            DateTime::make('Created At') -> onlyOnDetail(),
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
        return [
            new StartDateRange,
            new EndDateRange,
            new ChargingType,
            new ChargerType,
            new OrderType,
        ];
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
        return [
            new ExportOrders,
        ];
    }

    /**
     * Create policy.
     * 
     * @param Request $request
     * @return mixed
     */
    public static function authorizedToCreate(Request $request)
    {
        return false;
    }

    /**
     * Delete policy.
     * 
     * @param Request $request
     * @return mixed
     */
    public function authorizedToDelete(Request $request)
    {
        return false;
    }

    /**
     * Update policy.
     * 
     * @param Request $request
     * @return mixed
     */
    public function authorizedToUpdate(Request $request)
    {
        return false;
    }
}
