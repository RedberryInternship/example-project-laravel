<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\BelongsTo;
use App\Nova\Filters\Charger\Company;
use Laravel\Nova\Fields\BelongsToMany;
use App\Nova\Actions\ExportChargerOrders;
use App\Nova\Filters\Charger\ChargerType;
use Spatie\NovaTranslatable\Translatable;
use App\Nova\Filters\Charger\ChargerStatus;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Number;

class Charger extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Charger';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'name',
        'code',
    ];

    /**
     * Grouping nova resource.
     */
    public static $group = 'Charger Resources';

    /**
     * Eager Loading.
     *
     * @var string
     */
    public static $with = [ 'charger_connector_types.charging_prices', 'charger_connector_types.fast_charging_prices' ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()
                ->sortable(),

            Translatable::make([
                Text::make('Name')
                    ->sortable()
                    ->rules('max:255')
            ])->locales(['en', 'ru', 'ka']),

            ID::make('Charger Id')
                ->sortable()
                ->hideFromIndex(),

            Text::make('Code'),

            Translatable::make([
                Text::make('Description')->sortable() -> hideFromIndex()
            ])->locales(['en', 'ru', 'ka']),

            Translatable::make([
                Text::make('Location')
                    ->sortable() -> hideFromIndex()
            ])->locales(['en', 'ru', 'ka']),

            Boolean::make('Public')
                ->trueValue(1)
                ->falseValue(0),

            Boolean::make('Is Paid')
                ->trueValue(1)
                ->falseValue(0),
            
            Boolean::make('Is Penalty Enabled', 'penalty_enabled')
                ->trueValue(1)
                ->falseValue(0),
            
            Number::make('Kilowatt Price', 'kilowatt_price')
                -> step(0.01)
                -> hideFromIndex(),
            
            Text::make('Status') -> readonly(),

            Text::make('Lat'),

            Text::make('Lng'),

            HasMany::make('Charger Connector Types'),
            
            BelongsTo::make('Company'),
            
            Image::make('Image'),

            BelongsToMany::make('Business Services')
                -> nullable()
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
            new ChargerStatus,
            new ChargerType,
            new Company,
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
            (new ExportChargerOrders) -> onlyOnDetail(),
        ];
    }
}
