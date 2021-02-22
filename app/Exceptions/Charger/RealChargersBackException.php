<?php

namespace App\Exceptions\Charger;

use Exception;
use App\Traits\Message;
use App\Enums\GeneralError as GeneralErrorEnum;

class RealChargersBackException extends Exception
{
    use Message;

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
