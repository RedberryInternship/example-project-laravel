<?php

namespace App\Http\Requests\User;

use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\ValidatesWhenResolved;

class RegistrationRequest extends FormRequest implements ValidatesWhenResolved
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
            'first_name'   => 'required|string|max:255',
            'last_name'    => 'required|string|max:255',
            'password'     => 'required|string',
            'phone_number' => 'required|string|unique:users'
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
     * Create User in database.
     */
    public function createUser()
    {
        $request = $this -> all();

        $user = User::create([
            'active'       => 1,
            'verified'     => 1,
            'email'        => $request['email'] ?? null,
            'last_name'    => $request['last_name'],
            'first_name'   => $request['first_name'],
            'phone_number' => $request['phone_number'],
            'password'     => bcrypt($request['password'])
        ]);

        return $user;
    }
}
