<?php

namespace App\Http\Requests\Business\Chargers;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\ValidatesWhenResolved;

class AddFastPrice extends FormRequest implements ValidatesWhenResolved
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
        'charger_connector_type_id' => 'nullable|numeric',
        'start_minutes'             => 'required|numeric',
        'end_minutes'               => 'required|numeric',
        'price'                     => 'required|numeric',
    ];
  }
}
