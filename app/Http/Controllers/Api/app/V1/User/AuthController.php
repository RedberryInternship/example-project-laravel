<?php

namespace App\Http\Controllers\Api\app\V1\User;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    /**
     * Authenticate(Login) User.
     * 
     * @param Request $request
     * 
     * @return JSON
     */
    public function __invoke(Request $request)
    {
        $credentials = $request -> only('phone_number', 'password');

        if ( ! $token = auth('api') -> attempt($credentials))
        {
            return response() -> json(['error' => 'User Not Found'], 403);
        }

        return response() -> json(User::respondWithToken($token));
    }
}
