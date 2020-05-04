<?php

namespace App\Http\Requests\User;

use App\User;
use Carbon\Carbon;
use App\TempSmsCode;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\ValidatesWhenResolved;

class VerifyCodeRequest extends FormRequest implements ValidatesWhenResolved
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
            'code'         => 'required',
            'phone_number' => 'required'
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
     * Verify User's entered Code.
     * 
     * @return boolean
     */
    public function verifyCode()
    {
        $code        = $this -> get('code');
        $phoneNumber = $this -> get('phone_number');

        $user = User::where('phone_number', $phoneNumber) -> first();

        if ($user) return false;

        $tempSmsCode = TempSmsCode::where([
            'code'         => $code,
            'phone_number' => $phoneNumber
        ]) -> first();

        if ( ! $tempSmsCode) return false;

        $totalDuration = Carbon::now() -> diffInMinutes($tempSmsCode -> updated_at);

        if ($totalDuration > 3) return false;

        $tempSmsCode -> update([
            'status' => 1
        ]);

        return true;
    }
}
