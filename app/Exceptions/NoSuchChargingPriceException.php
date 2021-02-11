<?php

namespace App\Exceptions;

use Exception;
use App\Traits\Message;

class NoSuchChargingPriceException extends Exception
{
    use Message;

    /**
     * Render the exception as an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        return response() -> json(
            [
                'message'       => $this -> messages ['something_went_wrong'],
                'status'        => 'There is no appropriate charging price in database.',
                'status_code'   => 500,
            ], 500
        );    
    }
}
