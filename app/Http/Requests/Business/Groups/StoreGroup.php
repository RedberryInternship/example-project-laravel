<?php

namespace App\Http\Requests\Business\Groups;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\ValidatesWhenResolved;

use App\Group;

class StoreGroup extends FormRequest implements ValidatesWhenResolved
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
        'name' => [
          'required',
          function($attr, $value, $fail ) {
            $doesSuchGroupExists = Group :: whereUserId( auth() -> user() -> id ) -> whereName( $value ) -> exists();

            if( $doesSuchGroupExists )
            {
              $fail('Group with such name already exists...');
            }
          }
        ],
    ];
  }
}
