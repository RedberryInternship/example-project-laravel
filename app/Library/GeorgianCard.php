<?php

namespace App\Library;

use Redberry\GeorgianCardGateway\Contracts\GeorgianCardHandler;

use App\UserCard;
use App\User;

class GeorgianCard implements GeorgianCardHandler
{
  /**
   * Get primary transaction id
   * for recurrent transactions.
   * 
   * @param   int $userCardId
   * @return  string|null
   */
  public function getPrimaryTransactionId( $userCardId )
  {
    $userCard = UserCard :: find( $userCardId );

    if( $userCard )
    {
      return $userCard -> transaction_id;
    }
  }

  /**
   * Save card with user id and
   * user card information.
   * 
   * @param   int     $primaryTrixId
   * @param   int     $userId
   * @param   object  $userCardInfo
   * 
   * @return  void
   */
  function saveCard( $primaryTrixId, $userId, $userCardInfo )
  {
    $user     = User :: with( 'user_cards' ) -> find( $userId );
    $default  = $user -> user_cards -> count() == 0;

    $user -> user_cards() -> create(
      [
        'masked_pan'      => $userCardInfo -> masked_pan,
        'transaction_id'  => $primaryTrixId,
        'card_holder'     => $userCardInfo -> card_holder,
        'rrn'             => $userCardInfo -> rrn,
        'default'         => $default,
        'active'          => true,
      ]
    );
  }

  /**
   * Update user card RRN for
   * refund operations.
   * In order to refund you always need 
   * last transaction/operation identifier 
   * which is RRN.
   * 
   * @param   int     $userCardId
   * @param   string  $RRN
   */
  function updateCardRRN( $userCardId, $RRN )
  {
    $userCard = UserCard :: find( $userCardId );
    $userCard -> update([ 'rrn' => $RRN ]);
  }

  /**
   * Success method will be executed if
   * transaction is to end successfully.
   * 
   * @return mixed
   */
  public function success()
  {
    dump( 'Success' );
  }

  /**
   * Failed method will be executed if
   * transaction is to end with failure.
   * 
   * @return mixed
   */
  function failed()
  {
    dump( 'Failure' );
  }
}