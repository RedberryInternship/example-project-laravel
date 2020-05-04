<?php

namespace App\Http\Controllers\Api\app\V1\User;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\SendCodeRequest;

class CodeController extends Controller
{
    /**
     * Send Tempolary Code to User.
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

        return response() -> json([], 200);
    }
}
