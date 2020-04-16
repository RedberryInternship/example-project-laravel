<?php

namespace App\Exceptions\Charger;

use Exception;

class StopChargingException extends Exception
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
            'message' => $this -> message ?: 'Stop charging request couldn\'t be confirmed.',
            'code'    => $this -> code    ?: 500,
        ], $this -> code ?: 500 );
    }
}
