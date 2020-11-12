<?php

namespace App\Http\Requests\Business\Profile;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\ValidatesWhenResolved;

class UpdateInfo extends FormRequest implements ValidatesWhenResolved
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
      'first_name'   => 'required',
      'phone_number' => 'required',
      'email'        => 'email|required',
      'password'     => 'nullable|confirmed|min:6',
    ];
  }
}
