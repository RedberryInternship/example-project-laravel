<?php

namespace App\Library\Payments;

use App\Enums\PaymentType as PaymentTypeEnum;

use App\Payment as PaymentModel;
use App\User;

class Payment
{
  /**
   * Determine if it should save card or pay
   * and proceed accordingly.
   * 
   * @return void
   */
  public function update()
  {
    if( $this -> shouldSaveUserCard() )
    {
      $this -> saveUserCard();
    }
    else
    {
      $this -> pay();
    }
  }

  /**
   * Determine if it should save user card.
   * 
   * @return bool
   */
  private function shouldSaveUserCard()
  {
    return  request() -> get( 'o_type' ) == 'register';
  }

  /**
   * Save user card.
   * 
   * @return void
   */
  private function saveUserCard()
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
   * Make payment.
   * 
   * @return void
   */
  private function pay()
  {
    $userCardId = request() -> get( 'o_user_card_id'  );
    $orderId    = request() -> get( 'o_order_id'      );
    $userId     = request() -> get( 'o_order_id'      );
    $trxId      = request() -> get( 'trx_id'          );
    $price      = request() -> get( 'o_amount'        );
    $RRN        = request() -> get( 'p_rrn'           );
    $type       = PaymentTypeEnum :: CUT;

    PaymentModel :: create(
      [
        'user_card_id' => $userCardId,
        'order_id'     => $orderId,
        'user_id'      => $userId,
        'trx_id'       => $trxId,
        'price'        => $price,
        'prrn'         => $RRN,
        'type'         => $type,
      ]
    );
  }
}