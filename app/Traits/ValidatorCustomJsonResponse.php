<?php

namespace App\Traits;

use Illuminate\Validation\ValidationException;
use Illuminate\Http\JsonResponse;

trait ValidatorCustomJsonResponse{

  private function respond($validator, $status_code = 400)
  {
    if($validator -> fails())
    {
        $resData = array_merge(
            [   
                'message' => 'The given data was invalid.',
                'status_code' => $status_code,
            ],
            [
                'errors' => $validator -> errors()
            ]
        );

        $response = new JsonResponse($resData, 400);

        $e = new ValidationException($validator, $response);
        throw $e;   
    }       
  }
}