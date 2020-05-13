<?php

namespace App\Exceptions\Charger;

use Exception;

use App\Traits\Message;
use App\Enums\GeneralError as GeneralErrorEnum;

class StopChargingException extends Exception
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
            'message'     => $this -> messages [ 'cant_stop_charging' ],
            'status'      => $this -> message ?: GeneralErrorEnum :: CANT_CONFIRM_CHARGING_FINISH,
            'status_code' => $this -> code    ?: 400,
        ], $this -> code ?: 400 );
    }
}
