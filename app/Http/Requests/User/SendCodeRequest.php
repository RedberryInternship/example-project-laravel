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

    const USER_ALREADY_EXISTS   = 'USER_ALREADY_EXISTS';
    const USER_DOES_NOT_EXISTS  = 'USER_DOES_NOT_EXISTS';

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
            'type'     => 'required|string',
            'phone_number' => 'required|string'
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

    /**
     * User registration.
     */
    public function userRegistration()
    {
        $user = $this -> user();

        if($user)
        {
            return response() -> json(
                [
                    'status' => self :: USER_ALREADY_EXISTS,
                    'phone_number' => request() -> get( 'phone_number' ),
                ], 422
            );
        }

        return $this -> proceedSuccessfully();
    }

    /**
     * Password reset.
     */
    public function passwordReset()
    {
        $user = $this -> user();

        if(! $user)
        {
            return response() -> json(
                [
                    'status' => self :: USER_DOES_NOT_EXISTS,
                    'phone_number' => request() -> get( 'phone_number' ),
                ], 422
            );
        }

        return $this -> proceedSuccessfully();
    }

    /**
     * Phone number update.
     */
    public function phoneNumberUpdate()
    {
        $user = $this -> user();

        if(! $user)
        {
            return response() -> json(
                [
                    'status' => self :: USER_DOES_NOT_EXISTS,
                    'phone_number' => request() -> get( 'phone_number' ),
                ], 422
            );
        }

        return $this -> proceedSuccessfully();
    }

    /**
     * Proceed successfully.
     */
    public function proceedSuccessfully()
    {
        $code = $this -> generateCode();
        $this -> updateOrCreateCode($code);
        User::sendSms(request() -> get('phone_number'), $code);
        return response() -> json(
            [
                'phone_number' => request() -> get('phone_number')
            ]
        );
    }

    /**
     * Default behavior.
     */
    public function default()
    {
        return new \Exception("Unprocessable data", 422);
    }

    /**
     * Get user.
     * 
     * @return User
     */
    public function user()
    {
        return User::findBy('phone_number', request()->get('phone_number'));
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

    /**
     * Generate code.
     * 
     * @return int
     */
    public function generateCode(): int
    {
        return rand(pow(10, 4-1), pow(10, 4)-1);
    }
}
