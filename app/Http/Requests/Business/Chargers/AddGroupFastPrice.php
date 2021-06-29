<?php

namespace App\Http\Requests\Business\Chargers;

use App\ChargerConnectorType;
use App\Rules\MaxAndMinPrice;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\ValidatesWhenResolved;

class AddGroupFastPrice extends FormRequest implements ValidatesWhenResolved
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
    $connectorId = request()->get('charger_connector_type_id');
    $connector = ChargerConnectorType::findOrFail($connectorId);

    return [
        'start_minutes'             => 'required|numeric',
        'end_minutes'               => 'required|numeric',
        'price'                     => [
          'required',
          'numeric',
          new MaxAndMinPrice($connector->min_price, $connector->max_price),
        ],
    ];
  }
}
