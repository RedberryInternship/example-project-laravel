<?php

namespace App\Http\Requests\Business\Chargers;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\ValidatesWhenResolved;

class AddLvl2Price extends FormRequest implements ValidatesWhenResolved
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
      'start_time' => 'required',
      'end_time'   => 'required',
      'min_kwt'    => 'required',
      'max_kwt'    => 'required',
      'price'      => 'required|numeric',
    ];
  }
}
