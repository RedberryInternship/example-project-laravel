<?php

namespace App\Library;

use Redberry\GeorgianCardGateway\Contracts\GeorgianCardHandler;

use Illuminate\Http\Request;
use App\UserCard;
use App\User;

class GeorgianCard implements GeorgianCardHandler
{
  /**
   * Get primary transaction id
   * for recurrent transactions.
   * 
   * @param   Request $request
   * @return  string|null
   */
  public function getPrimaryTransactionId( Request $request )
  {

    $userCardId = $request -> get('o_user_card_id');
    $userCard   = UserCard :: find( $userCardId );

    if( $userCard )
    {
      return $userCard -> transaction_id;
    }
  }

  /**
   * Save card with user id and
   * user card information.
   * 
   * @param   Request  $request
   * 
   * @return  void
   */
  public function update( Request $request )
  {
    if( $this -> shouldSaveUserCard() )
    {
      $userId         = $request -> get( 'o_user_id'    );
      $primaryTrixId  = $request -> get( 'trx_id'       );
      $maskedPan      = $request -> get( 'p_maskedPan'  );
      $cardHolder     = $request -> get( 'p_cardholder' );
  
      $user           = User :: with( 'user_cards' ) -> find( $userId );
      $default        = $user -> user_cards -> count() == 0;
  
      $user -> user_cards() -> create(
        [
          'masked_pan'      => $maskedPan,
          'transaction_id'  => $primaryTrixId,
          'card_holder'     => $cardHolder,
          'default'         => $default,
          'active'          => true,
        ]
      );
    }
    else
    {
      // make payment 
    }
  }

  /**
   * Determine if it should save user card.
   * 
   * @return bool
   */
  private function shouldSaveUserCard()
  {
    return  ! request() -> has( 'o_user_card_id' );
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
  public function failure()
  {
    dump( 'Failure' );
  }
}