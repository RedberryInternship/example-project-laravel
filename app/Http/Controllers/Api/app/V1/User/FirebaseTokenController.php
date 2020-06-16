<?php

namespace App\Http\Controllers\Api\app\V1\User;

use App\Http\Controllers\Controller;

use App\Http\Requests\FirebaseTokenRequest;

use App\User;

class FirebaseTokenController extends Controller
{
  /**
   * Update app user's firebase token.
   * 
   * @param   FirebaseTokenRequest $request
   * @return  JSON
   */
  public function update( FirebaseTokenRequest $request )
  {
    $firebaseToken  = $request -> get( 'firebase_token' );
    $user           = User :: find( auth() -> user() -> id );
    
    $user -> update([ 'firebase_token' => $firebaseToken ]);

    return response() -> json([ 'success' => true ]);
  }
} 