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
        return response() -> json( $request -> all() );
        $credentials = $request -> only('phone_number', 'password');

        if ( ! $token = auth('api') -> attempt($credentials))
        {
            return response() -> json(['error' => 'User Not Found'], 403);
        }

        $user = auth('api') -> user();

        if ( ! $user -> active || ! $user -> verified)
        {
            return response() -> json(['error' => 'User Not Active'], 406);
        }

        if (strtolower($user -> role -> name) != 'regular')
        {
            return response() -> json(['error' => 'User Role mismatch'], 403);
        }

        return response() -> json(User::respondWithToken($token));
    }
}
