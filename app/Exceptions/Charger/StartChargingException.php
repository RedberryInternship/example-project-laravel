<?php

namespace App\Exceptions\Charger;

use Exception;

class StartChargingException extends Exception
{
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
            'message' => $this -> message ?: 'Charging couldn\'t be started.',
            'code'    => $this -> code    ?: 500,
        ], $this -> code ?: 500 );
    }
}
