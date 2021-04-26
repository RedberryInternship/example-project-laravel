<?php

namespace App\Http\Controllers\E2E;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\TempSmsCode;
use App\User;

class UserController extends Controller
{
  /**
   * Remove user from database.
   */
  public function destroy(Request $request) 
  {
    $data = $request->validate(
      [
        'phone_number' => 'string',
      ]
    );

    extract($data);

    $user = User::wherePhoneNumber($phone_number)->first();
    $user->delete();
  }


  /**
   * Get user otp code.
   */
  public function getUserOTP(Request $request)
  {
    $data = $request->validate(
      [
        'phone_number' => 'string',
      ]
    );

    extract($data);

    $tempCode = TempSmsCode::wherePhoneNumber($phone_number)->first();
    
    return response()->json(
      [
        'code' => $tempCode->code,
      ]
    );
  }
}