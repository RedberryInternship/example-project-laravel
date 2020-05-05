<?php

namespace App\Http\Controllers\Api\app\V1\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\PasswordResetRequest;

class PasswordController extends Controller
{
    /**
     * Reset User's Password.
     * 
     * @param PasswordResetRequest $request
     * 
     * @return JSON
     */
    public function reset(PasswordResetRequest $request)
    {
        $user = User::where('phone_number', $request -> phone_number) -> first();

        if ($user)
        {
            $user -> password = bcrypt($request -> password);
            $user -> save();

            $temp = TempSmsCode::where('phone_number', $request -> phone_number) -> delete();
        }

        return response() -> json([]);
    }
}
