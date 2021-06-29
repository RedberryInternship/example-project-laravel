<?php

namespace App\Http\Requests\Business\Chargers;

use App\ChargingPrice;
use App\Rules\MaxAndMinPrice;
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
    $chargingPriceId = (int) request()->route('charging_price');
    $chargingPrice = ChargingPrice::query()
      -> with('charger_connector_type')
      -> findOrFail($chargingPriceId);

    $connector = $chargingPrice->charger_connector_type;

    return [
        'start_time'           => 'required|string',
        'end_time'             => 'required|string',
        'max_kwt'              => 'required|numeric',
        'min_kwt'              => 'required|numeric',
        'price'                => [
          'required',
          'numeric',
          new MaxAndMinPrice(
            $connector->min_price, 
            $connector->max_price,
          ),
        ],
    ];
  }
}
