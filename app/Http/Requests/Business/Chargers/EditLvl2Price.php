<?php

namespace App\Http\Requests\Business\Chargers;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\ValidatesWhenResolved;

class EditLvl2Price extends FormRequest implements ValidatesWhenResolved
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
        'start_time'           => 'required|string',
        'end_time'             => 'required|string',
        'max_kwt'              => 'required|numeric',
        'min_kwt'              => 'required|numeric',
        'price'                => 'required|numeric',
    ];
  }
}
