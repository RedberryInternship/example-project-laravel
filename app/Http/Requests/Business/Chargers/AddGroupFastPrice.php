<?php

namespace App\Http\Requests\Business\Chargers;

use App\Group;
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
    $groupId = (int) request()->route('group_fast_price');
    $minPrice = null;
    $maxPrice = null;

    $group = Group::query()
      -> with('chargers.charger_connector_types')
      -> find($groupId);
    
    $group
      ->chargers
      ->each(function ($charger) use(&$minPrice, &$maxPrice) {
        $charger
          ->charger_connector_types
          ->each(function ($connector) use(&$minPrice, &$maxPrice) {
            if($connector->min_price !== null && $connector->max_price !== null)
            {
              if($minPrice === null) {
                $minPrice = $connector->min_price;
              } else if($connector->min_price < $minPrice)
              {
                $minPrice = $connector->min_price;
              }

              if($maxPrice === null)
              {
                $maxPrice = $connector->max_price;
              } else if($connector->max_price > $maxPrice)
              {
                $maxPrice = $connector->max_price;
              }
            }
          });
      });
  
    return [
        'start_minutes'             => 'required|numeric',
        'end_minutes'               => 'required|numeric',
        'price'                     => [
          'required',
          'numeric',
          new MaxAndMinPrice(
            $minPrice,
            $maxPrice,
          ),
        ],
    ];
  }
}
