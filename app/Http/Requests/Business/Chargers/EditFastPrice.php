<?php

namespace App\Http\Requests\Business\Chargers;

use App\FastChargingPrice;
use App\Rules\MaxAndMinPrice;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\ValidatesWhenResolved;

class EditFastPrice extends FormRequest implements ValidatesWhenResolved
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
    $fastChargingPriceId = (int) request()->route('fast_charging_price');
    $fastChargingPrice = FastChargingPrice::query()
      -> with('charger_connector_type')
      -> findOrFail($fastChargingPriceId);

    $connector = $fastChargingPrice->charger_connector_type;

    return [
        'start_minutes'             => 'required|numeric',
        'end_minutes'               => 'required|numeric',
        'price'                     => [
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
