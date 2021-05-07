<?php

namespace App\Http\Controllers\E2E;

use App\User;
use App\Favorite;
use App\TempSmsCode;
use App\UserCarModel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
  /**
   * Remove user from database.
   */
  public function destroy(Request $request) 
  {
    if($request->has('phone_number'))
    {
      $user = User::wherePhoneNumber($request->phone_number)->first();
      
      if($user)
      {
        $user->delete();
      }
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

  /**
   * Reset user password.
   */
  public function resetPassword(Request $request)
  {
    if(!$request->phone_number || !$request->previous_password) 
    {
      abort(Response::HTTP_BAD_REQUEST);
    }

    $user = User::wherePhoneNumber($request->phone_number)->firstOrFail();
    $user->password = bcrypt($request->previous_password);
    $user->save();
  }

  /**
   * Remove user favorite chargers.
   */
  public function clearFavorites(Request $request)
  {
    if(!$request->phone_number) 
    {
      abort(Response::HTTP_BAD_REQUEST);
    }

    $user = User::wherePhoneNumber($request->phone_number)->firstOrFail();

    Favorite::whereUserId($user->id)->delete();
  }

  /**
   * Remove cars from user.
   */
  public function clearCars(Request $request)
  {
    if(!$request->phone_number) 
    {
      abort(Response::HTTP_BAD_REQUEST);
    }
    $user = User::wherePhoneNumber($request->phone_number)->firstOrFail();
    UserCarModel::whereUserId($user->id)->delete();
  }
}