<?php

namespace App\Http\Requests\User;

use App\User;
use App\UserCarModel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\ValidatesWhenResolved;

class StoreCarRequest extends FormRequest implements ValidatesWhenResolved
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
            'car_model_id' => [
                'required',
                function( $attribute, $value, $fail ) {
                    $user = auth('api') -> user();
                    $car = UserCarModel :: where(
                        [
                            'user_id' => $user -> id,
                            'user_car_model' => $value,
                        ]
                    ) -> first();

                    if( $car ) 
                    {
                        $fail('Car already exists in database.');
                    }
                },
            ],

        ];
    }

}
