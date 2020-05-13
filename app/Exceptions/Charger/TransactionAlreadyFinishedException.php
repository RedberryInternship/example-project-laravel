<?php

namespace App\Exceptions\Charger;

use Exception;
use App\Traits\Message;
use App\Enums\GeneralError as GeneralErrorEnum;

class TransactionAlreadyFinishedException extends Exception
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
            'message'     => $this -> messages [ 'charger_transaction_already_stopped' ],
            'status'      => GeneralErrorEnum :: CHARGING_ALREADY_FINISHED,
            'status_code' => 400,
        ], $this -> code ?: 400 );
    }
}
