<?php

namespace App\Library\Entities\GeorgianCard;

use Illuminate\Http\Request;

use App\UserCard;

class PrimaryTRXGetter
{
  public static function get()
  {
    $userCardId = request() -> get( 'o_user_card_id' );
    $userCard   = UserCard :: find( $userCardId );

    if( $userCard )
    {
      return $userCard -> transaction_id;
    }
  }
}