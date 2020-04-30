<?php

namespace App\Traits;

use Illuminate\Validation\ValidationException;
use Illuminate\Http\JsonResponse;

trait ValidatorCustomJsonResponse{

  private function respond($validator, $status_code = 400, $message = null)
  {
    if($validator -> fails())
    {
        $resData = array_merge(
            [   
                'message' => $message ?: 'The given data was invalid.',
                'status_code' => $status_code ?: 500,
            ],
            [
                'errors' => $validator -> errors()
            ]
        );

        $response = new JsonResponse($resData, $status_code ?: 500);

        $e = new ValidationException($validator, $response);
        throw $e;   
    }       
  }
}
