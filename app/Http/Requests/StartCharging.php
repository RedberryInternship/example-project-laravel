<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\ValidatorCustomJsonResponse as Response;

class StartCharging extends FormRequest
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
            'charger_connector_type_id' => 'required|integer',
            'charging_type'             => 'required|string',
        ];
    }

    public function withValidator($validator)
    {
        $this -> respond($validator);
    }
}
