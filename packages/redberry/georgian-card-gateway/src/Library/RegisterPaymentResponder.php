<?php

namespace Redberry\GeorgianCardGateway\Library;

use Redberry\GeorgianCardGateway\Responses\RegisterPayment;

class RegisterPaymentResponder
{
  private $handler;

  /**
   * Set Georgian Card handler.
   */
  public function __construct()
  {
    $this -> handler = resolve( 'redberry.georgian-card.handler' );
  }

  /**
   * Respond with register payment xml.
   * 
   * @return xml
   */
  public function respond()
  {
    $resultCode         = request() -> get( 'result_code'  );
    $registerPayment    = new RegisterPayment;

    $resultDesc = $resultCode == 1 ? 'OK' : 'Temporary unavailable';

    $registerPayment -> setResultCode( $resultCode );
    $registerPayment -> setResultDesc( $resultDesc );

    if( $this -> isTransactionSuccessful() )
    {
      if( $this -> shouldSaveCard() )
      {
        $this -> saveCard();
      }
      else
      {
        $this -> updateCardRRN();
      }
    }

    return $registerPayment -> response();
  }

  /**
   * Determine if transaction is successful.
   * 
   * @return bool
   */
  private function isTransactionSuccessful()
  {
    return request() -> get( 'result_code'  ) == 1;
  }

  /**
   * Determine if card should be saved.
   * 
   * @return bool
   */
  private function shouldSaveCard()
  {
     return ! request() -> get( 'o_user_card_id' );
  }

  /**
   * Save card.
   * 
   * @return void
   */
  private function saveCard()
  {
    $userId         = request() -> get( 'o_user_id' );
    $primaryTrixId  = request() -> get( 'trx_id' );
    $userCardInfo   = $this     -> userCardInfo();

    $this 
      -> handler
      -> saveCard(
        $primaryTrixId,
        $userId,
        $userCardInfo,
      );
  }

  /**
   * Update user card RRN.
   * 
   * @return void
   */
  private function updateCardRRN()
  {
    $userCardId = request() -> get( 'o_user_card_id' );
    $RRN        = request() -> get( 'p_rrn' );

    $this 
      -> handler
      -> updateCardRRN(
        $userCardId,
        $RRN,
      );
  }

  /**
   * Extract user card info from Georgian Card Request.
   * 
   * @return object
   */
  private function userCardInfo(): object
  {
    $userCard = [
      'amount'        => request() -> get( 'amount'         ),
      'expiry_date'   => request() -> get( 'p_expiryDate'   ),
      'masked_pan'    => request() -> get( 'p_maskedPan'    ),
      'card_holder'   => request() -> get( 'p_cardholder'   ),
      'rrn'           => request() -> get( 'p_rrn'          ),
    ];

    return ( object ) $userCard;
  }
}