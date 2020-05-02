<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

use App\ChargerConnectorType;

use App\Facades\Charger;

class BusyCharger implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $chargerConnectorType   = ChargerConnectorType :: with( 'charger' ) -> find( $value );
        $charger                = $chargerConnectorType -> charger;
        
        return Charger :: isChargerFree( $charger -> charger_id );
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Charger is not free.';
    }
}
