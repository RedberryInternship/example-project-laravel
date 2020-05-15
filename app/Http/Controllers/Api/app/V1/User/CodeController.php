<?php

namespace App\Http\Controllers\Api\app\V1\User;

use App\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\SendCodeRequest;
use App\Http\Requests\User\VerifyCodeRequest;

class CodeController extends Controller
{
    /**
     * Send Temporary Code to User.
     * 
     * @param SendCodeRequest $request
     * 
     * @return JSON
     */
    public function sendCode(SendCodeRequest $request)
    {
        $code = rand(pow(10, 4-1), pow(10, 4)-1);
        
        $request -> updateOrCreateCode($code);

        User::sendSms($request -> get('phone_number'), $code);

        return response() -> json([
            'phone_number' => $request -> get('phone_number')
        ]);
    }

    /**
     * Verify Temporary Code.
     * 
     * @param VerifyCodeRequest $request
     * 
     * @return JSON
     */
    public function verifyCode(VerifyCodeRequest $request)
    {
        if ( ! $request -> verifyCode())
        {
            return response() -> json(['error' => ['verified' => false]], 403);
        }

        return response() -> json([
            'phone_number' => $request -> get('phone_number')
        ]);
    }

    /**
     * Verify Temporary Code For Password Recovery.
     * 
     * @param VerifyCodeRequest $request
     * 
     * @return JSON
     */
    public function verifyCodeForPasswordRecovery(VerifyCodeRequest $request)
    {        
        if ( ! $request -> verifyCode(true))
        {
            return response() -> json(['error' => ['verified' => false]], 403);
        }

        return response() -> json([
            'phone_number' => $request -> get('phone_number')
        ]);
    }
}
