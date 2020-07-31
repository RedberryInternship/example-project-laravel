<?php

namespace App\Library\Entities\GeorgianCard;

use App\Library\Entities\GeorgianCard\PaymentStatusChecker;

use App\User;

class UserCardSaver
{
  /**
   * Save user card.
   * 
   * @return void
   */
  public static function save()
  {
    $userId         = request() -> get( 'o_user_id'    );
    $primaryTrixId  = request() -> get( 'trx_id'       );
    $maskedPan      = request() -> get( 'p_maskedPan'  );
    $cardHolder     = request() -> get( 'p_cardholder' );
    $RRN            = request() -> get( 'p_rrn'        );

    $user           = User :: with( 'user_cards' ) -> find( $userId );
    $default        = $user -> user_cards -> count() == 0;

    $user -> user_cards() -> create(
      [
        'masked_pan'      => $maskedPan,
        'transaction_id'  => $primaryTrixId,
        'card_holder'     => $cardHolder,
        'prrn'            => $RRN,
        'default'         => $default,
        'active'          => true,
      ]
    );
  }

  /**
   * Determine if it should save user card.
   * 
   * @return bool
   */
  public static function shouldSaveUserCard()
  {
    return  request() -> get( 'o_type' ) == 'register';
  }
}