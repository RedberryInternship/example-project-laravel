<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\ValidatorCustomJsonResponse as Response;

use App\Rules\ModelHasRelation;

use App\ChargerConnectorType;

class StopCharging extends FormRequest
{

    use Response;

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
                new ModelHasRelation( ChargerConnectorType::class, 'charger' ),
                new ModelHasRelation( ChargerConnectorType::class, 'connector_type' ),
            ],
        ];
    }

    public function messages()
    {
        return [
            'charger_connector_type_id.required' => 'charger_connector_type_id is required',
            'charger_connector_type_id.integer'  => 'charger_connector_type_id must be integer.',
            'charger_connector_type_id.exists'   => 'Such charger connector type doesn\'t exists in db.',
        ];
    }

    public function withValidator( $validator )
    {
        $this -> respond( $validator, 422, $this -> messages [ 'something_went_wrong' ] );
    }
}
