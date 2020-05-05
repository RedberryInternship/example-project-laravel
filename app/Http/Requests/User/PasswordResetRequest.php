<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\ValidatesWhenResolved;

class PasswordResetRequest extends FormRequest implements ValidatesWhenResolved
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
            //
        ];
    }

    /**
     * Handle Failed Validation.
     * 
     * @param Validator $validator
     */
    protected function failedValidation(Validator $validator)
    {
        $jsonResponse = response() -> json(['error' => $validator -> errors()], 422);

        throw new HttpResponseException($jsonResponse);
    }
}
