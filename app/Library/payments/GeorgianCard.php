<?php

namespace App\Library\Payments;

use Redberry\GeorgianCardGateway\Contracts\GeorgianCardHandler;
use Redberry\GeorgianCardGateway\Refund;

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
    $payment = new Payment;
    $payment -> update();
  }

  /**
   * Success method will be executed if
   * transaction is to end successfully.
   * 
   * @return mixed
   */
  public function success()
  {
    if( request() -> get( 'type' ) == 'register' )
    {
      $userId   = request() -> get( 'user_id' );
      $userCard = User :: find( $userId ) -> user_cards() -> last();
      
      $refunder = new Refund;
      $refunder -> setAmount( 20                          );
      $refunder -> setRRN   ( $userCard -> prrn           );
      $refunder -> setTrxId ( $userCard -> transaction_id );

      $refunder -> execute();
    }

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