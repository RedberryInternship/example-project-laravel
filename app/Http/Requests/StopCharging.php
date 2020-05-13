<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use App\Traits\ValidatorCustomJsonResponse as Response;
use App\Traits\Message;

class StopCharging extends FormRequest
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
            'order_id' => [
                'bail',
                'required',
                'integer',
                'exists:orders,id',
            ],
        ];
    }

    public function messages()
    {
        return [
            'order_id.required' => 'order_id is required.',
            'order_id.integer'  => 'order_id must be integer.',
            'order_id.exists'   => 'Such order doesn\'t exists in db.',
        ];
    }

    public function withValidator( $validator )
    {
        $this -> respond( $validator, 422, $this -> messages [ 'something_went_wrong' ] );
    }
}
