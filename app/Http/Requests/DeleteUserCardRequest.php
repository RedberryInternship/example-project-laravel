<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DeleteUserCardRequest extends FormRequest
{
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
            'user_card_id' => [
                'required',
                Rule :: exists( 'user_cards', 'id' )    
                     -> where( 'user_id'    , auth() -> user() -> id )
                     -> where( 'active'     , true )
            ]
        ];
    }
}
