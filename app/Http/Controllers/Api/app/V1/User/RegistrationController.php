<?php

namespace App\Http\Controllers\Api\app\V1\User;

use App\TempSmsCode;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\RegistrationRequest;

class RegistrationController extends Controller
{
    /**
     * Register User.
     * 
     * @param RegistrationRequest @request
     */
    public function __invoke(RegistrationRequest $request)
    {
        $user = $request -> createUser();
        
        TempSmsCode::deleteCodesByPhoneNumber($request -> get('phone_number'));

        $token = JWTAuth::fromUser($user);

        return response() -> json([
            'user'  => $user,
            'token' => $token
        ]);
    }
}
