<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\ValidatorCustomJsonResponse as Response;
use App\Traits\Message;

use App\Rules\ModelHasRelation;
use App\ChargerConnectorType;
use App\Rules\BusyCharger;

use ReflectionClass;
use ReflectionMethod;

class StartCharging extends FormRequest
{

    use Response,
        Message;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'charger_connector_type_id' => [
                'bail',
                'required',
                'integer',
                'exists:charger_connector_types,id',
                new ModelHasRelation( ChargerConnectorType::class, 'charger'),
                new ModelHasRelation( ChargerConnectorType::class, 'connector_type'),
                new BusyCharger(),
            ],
            'charging_type'             => [
                'required',
                'string',
                'in:BY_AMOUNT,FULL_CHARGE',
            ],
            'price'                     => [
                'required_if:charging_type,BY_AMOUNT',
                'numeric',
            ] 
        ];
    }

    public function messages()
    {
        return [
            'charger_connector_type_id.required' => 'charger_connector_type_id is required',
            'charger_connector_type_id.integer'  => 'charger_connector_type_id must be integer.',
            'charger_connector_type_id.exists'   => 'Such charger connector type doesn\'t exists in db.',

            'charging_type.required'             => 'Charging Type is required.',
            'charging_type.string'               => 'Charging Type should be string.',
            'charging_type.in'                   => 'Charging Type should be BY_AMOUNT or FULL_CHARGE.',
            
            'price.required_if'                  => 'Price field is required.',
            'price.numeric'                      => 'Price must be numeric.',
        ];
    }

    public function withValidator($validator)
    {
        $chargerIsFree = $this -> isChargerFree( $validator );
        
        if( ! $chargerIsFree )
        {
            $this -> respond($validator, 400, $this -> messages [ 'charger_is_not_free' ]);
        }
        else
        {
            $this -> respond($validator, 422, $this -> messages [ 'something_went_wrong' ]);
        }
    }

    private function isChargerFree($validator)
    {
        $data                   = $validator -> getData();
        $chargerConnectorTypeId = $data [ 'charger_connector_type_id' ];
        $busyCharger            = new BusyCharger();
        
        return $busyCharger -> passes( null, $chargerConnectorTypeId );
    }
}
