<?php

namespace App\Http\Controllers\E2E;

use App\User;
use App\UserCarModel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

class ClearCarsController extends Controller
{
  /**
  * Remove cars from user.
  */
  public function __invoke(Request $request)
  {
    if(!$request->phone_number) 
    {
      abort(Response::HTTP_BAD_REQUEST);
    }
    $user = User::wherePhoneNumber($request->phone_number)->firstOrFail();
    UserCarModel::whereUserId($user->id)->delete();
  }
}