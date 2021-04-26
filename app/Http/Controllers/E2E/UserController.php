<?php

namespace App\Http\Controllers\E2E;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\TempSmsCode;
use App\User;
use Illuminate\Http\Response;

class UserController extends Controller
{
  /**
   * Remove user from database.
   */
  public function destroy(Request $request) 
  {
    if($request->has('phone_number'))
    {
      $user = User::wherePhoneNumber($request->phone_number)->firstOrFail();
      $user->delete();
    }
  }


  /**
   * Get user otp code.
   */
  public function getUserOTP(Request $request)
  {
    if(!$request->phone_number) 
    {
      abort(Response::HTTP_BAD_REQUEST);
    }

    $tempCode = TempSmsCode::query()
      ->wherePhoneNumber($request->phone_number)
      ->firstOrFail();
    
    return response()->json(
      [
        'code' => $tempCode->code,
      ]
    );
  }
}