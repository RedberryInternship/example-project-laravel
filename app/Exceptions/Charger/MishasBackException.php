<?php

namespace App\Exceptions\Charger;

use Exception;
use App\Traits\Message;

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
            'status'  => 'Something went wrong in Misha\'s Side',
            'code'    => 400,
        ], 400);
    }
}
