<?php

namespace App\Library\Entities\GeorgianCard;

use App\UserCard;

use Redberry\GeorgianCardGateway\Responses\PaymentAvail;

class PrimaryTRXSetter
{
  public static function set( PaymentAvail &$data )
  {
    $userCardId = request() -> get( 'o_user_card_id' );
    $userCard   = UserCard :: find( $userCardId );

    if( $userCard )
    {
      $data -> setPrimaryTrxPcid( $userCard -> transaction_id );
    }
  }
}