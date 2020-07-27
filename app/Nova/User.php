<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Gravatar;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\BelongsToMany;


class User extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */

    public static $model = 'App\User';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'first_name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'first_name', 'last_name', 'email',
    ];

    /**
     * The way, how user will be displayed from other resources.
     */
    public function title()
    {
        return $this -> first_name . ' ' . $this -> last_name;
    }

    /**
     * Grouping nova resource.
     */
    public static $group = 'User Resources';

    /**
     * Eager Loading.
     *
     * @var string
     */
    public static $with = ['role'];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),

            BelongsTo::make('Company'),

            BelongsTo::make('Role'),

            Gravatar::make(),
            
            Text::make('first_name')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('last_name')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('phone_number')
                ->rules('required','string', 'min:9')
                ->creationRules('unique:users,phone_number')
                ->updateRules('unique:users,phone_number,{{resourceId}}'),           

            Text::make('Email')
                ->sortable()
                ->rules('required', 'email', 'max:254')
                ->creationRules('unique:users,email')
                ->updateRules('unique:users,email,{{resourceId}}'),

            Boolean::make('active')
                ->trueValue(1)
                ->falseValue(0),

            Boolean::make('verified')
                ->trueValue(1)
                ->falseValue(0),

            BelongsToMany::make('User Car Model','car_models', 'App\Nova\CarModel'),

            HasMany::make('User Charger', 'user_chargers', 'App\Nova\ChargerUser'),
            
            Password::make('Password')
                ->onlyOnForms()
                ->creationRules('required', 'string', 'min:8')
                ->updateRules('nullable', 'string', 'min:8'),
                
            HasMany::make('User Cards'),

            HasMany::make('Orders'),
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
