<?php

namespace App\Http\Controllers\E2E;

use App\User;
use App\Favorite;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

class ClearFavoritesController extends Controller
{
  /**
   * Remove user favorite chargers.
   */
  public function __invoke(Request $request)
  {
    if(!$request->phone_number) 
    {
      abort(Response::HTTP_BAD_REQUEST);
    }

    $user = User::wherePhoneNumber($request->phone_number)->firstOrFail();

    Favorite::whereUserId($user->id)->delete();
  }
}