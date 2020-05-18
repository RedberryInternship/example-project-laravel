<?php

namespace App\Exceptions\Charger;

use Exception;
use App\Traits\Message;
use App\Enums\GeneralError as GeneralErrorEnum;

class MishasBackException extends Exception
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
            'message' => $this -> messages [ 'something_went_wrong' ],
            'status'  => GeneralErrorEnum :: SOMETHING_WENT_WRONG,
            'code'    => 400,
        ], 400);
    }
}
