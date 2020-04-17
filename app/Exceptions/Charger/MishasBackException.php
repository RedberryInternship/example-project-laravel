<?php

namespace App\Exceptions\Charger;

use Exception;

class MishasBackException extends Exception
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
            'message' => 'Something went wrong in Misha\'s Side',
            'code'    => 500
        ], 500);
    }
}
