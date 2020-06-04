<?php

namespace App\Library\Payments;

use Redberry\GeorgianCardGateway\Refund;

use App\User;

class SaveCardRefunder
{
  public static function RefundIfCardSaved()
  {
    if( request() -> get( 'type' ) == 'register' )
    {
      $userId   = request() -> get( 'user_id' );
      $userCard = User :: find( $userId ) -> user_cards() -> latest() -> first();
      
      $refunder = new Refund;
      $refunder -> setAmount( 20                          );
      $refunder -> setRRN   ( $userCard -> prrn           );
      $refunder -> setTrxId ( $userCard -> transaction_id );

      $refunder -> execute();
    }
  }
}