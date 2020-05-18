<?php

namespace App\Http\Requests\User;

use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\ValidatesWhenResolved;

class PasswordEditRequest extends FormRequest implements ValidatesWhenResolved
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
            'new_password' => 'required',
            'old_password' => 'required',
            'phone_number' => 'required'
        ];
    }

    /**
     * Edit User's Password.
     * 
     * @return void
     */
    public function editPassword()
    {
        $data = $this -> all();

        $user = User::where('phone_number', $data['phone_number']) -> first();
        
        if ($user && Hash::check($data['old_password'], $user -> password))
        {
            $user -> update([
                'password' => bcrypt($data['new_password'])
            ]);

            return true;
        }

        return false;
    }
}
