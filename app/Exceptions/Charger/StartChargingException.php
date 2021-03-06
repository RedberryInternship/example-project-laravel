<?php

namespace App\Exceptions\Charger;

use Exception;
use App\Traits\Message;

class StartChargingException extends Exception
{
    use Message;

    /**
     * Report the exception.
     *
     * @return void
     */
    public function report()
    {
        //
    }

    /**
     * Render the exception as an JSON response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function render($request)
    {
        return response() -> json([
            'message'     => $this -> messages [ 'cant_charge' ],
            'status'      => $this -> message ?: 'Charging couldn\'t be started!',
            'status_code' => $this -> code    ?: 500,
        ], $this -> code ?: 500 );
    }
}
