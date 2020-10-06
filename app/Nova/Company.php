<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\HasMany;
use Spatie\NovaTranslatable\Translatable;
use App\Enums\ContractMethod as ContractMethodEnum;
use Laravel\Nova\Fields\Number;

class Company extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Company';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * Grouping nova resource.
     * 
     * @var string
     */
    public static $group = 'User Resources';

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
        return [
            ID::make()
                ->sortable(),

            Translatable::make([
                Text::make('name')
            ])->locales(['en', 'ru', 'ka']),

            Text::make('Identification Code')
                ->sortable(),
            
            Date::make('Contract Started')
                ->sortable(),
            
            Date::make('Contract Ended')
                ->sortable(),

            Text::make('Bank Account'),

            Text::make('Address'),

            File::make('Contract File')
                ->disk('public'),

            Select::make('Contract Method') 
                -> options( ContractMethodEnum :: getConstants() ) 
                -> rules('required'),

            Number::make('Contract Value') -> step(0.01) -> rules('required'),

            HasMany::make('Users'),
            
            HasMany::make('Chargers'),
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
}
