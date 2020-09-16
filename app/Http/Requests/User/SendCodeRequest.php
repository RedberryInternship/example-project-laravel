<?php

namespace App\Http\Requests\User;

use App\User;
use App\TempSmsCode;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\ValidatesWhenResolved;

class SendCodeRequest extends FormRequest implements ValidatesWhenResolved
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
        $rules['phone_number'] = 'required';

        if ($this -> has('type'))
        {
            $user = User::findBy(
                'phone_number',
                $this -> get('phone_number')
            );

            if (
                ($this -> get('type') == 'phone_change' && ! $user) ||
                ($this -> get('type') == 'password_reset' && ! $user) ||
                ($this -> get('type') == 'registers' && $user)
            )
            {
                $rules['user'] = 'required';
            }
        }

        return $rules;
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

    /**
     * Update Code in Database if exists, otherwise create new record.
     * 
     * @param @code
     * 
     * @return void
     */
    public function updateOrCreateCode($code)
    {
        TempSmsCode::updateOrCreate(
            ['phone_number' => $this -> get('phone_number')],
            ['code'         => $code]
        ) -> first();
    }
}
