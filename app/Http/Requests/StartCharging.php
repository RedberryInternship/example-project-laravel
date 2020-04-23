<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\ValidatorCustomJsonResponse as Response;
use App\Traits\Message;

use App\ChargerConnectorType;
use App\Rules\ModelHasRelation;



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
            ],
            'charging_type'             => [
                'required',
                'string',
                'in:BY-AMOUNT,FULL-CHARGE',
            ],
            'price'                     => [
                'required_if:charging_type,BY-AMOUNT',
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
            'charging_type.in'                   => 'Charging Type should be BY-AMOUNT or FULL-CHARGE.',
            
            'price.required_if'                  => 'Price field is required.',
            'price.numeric'                      => 'Price must be numeric.',
        ];
    }

    public function withValidator($validator)
    {
        $this -> respond($validator, 422, $this -> messages [ 'something_went_wrong' ]);
    }
}
