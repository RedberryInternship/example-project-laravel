<?php

namespace App\Http\Controllers\Api\app\V1\User;

use App\TempSmsCode;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\PasswordEditRequest;
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
        $request -> changePassword();

        TempSmsCode::deleteCodesByPhoneNumber($request -> phone_number);

        return response() -> json([]);
    }

    /**
     * Edit User's password.
     * 
     * @param PasswordEditRequest $request
     * 
     * @return JSON
     */
    public function edit(PasswordEditRequest $request)
    {
        if ( ! $request -> editPassword())
        {
            return response() -> json(['error' => 'Incorrect phone number or old password'], 403);
        }

        return response() -> json([]);
    }
}
