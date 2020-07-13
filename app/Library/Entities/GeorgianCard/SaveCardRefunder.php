<?php

namespace App\Library\Entities\GeorgianCard;

use App\User;

use App\Library\Adapters\Payments\Refunder;

class SaveCardRefunder
{
  public static function RefundIfCardSaved()
  {
    if( request() -> get( 'type' ) == 'register' )
    {
      $userId   = request() -> get( 'user_id' );
      $userCard = User :: find( $userId ) -> user_cards() -> latest() -> first();
      
      $trxId    = $userCard -> transaction_id;
      $RRN      = $userCard -> prrn;
      $amount   = 20;

      sleep( 2 );

      Refunder :: refund( $trxId, $RRN, $amount );
    }
  }
}