<?php

namespace App\Library\Entities\Payments;

use Redberry\GeorgianCardGateway\Transaction;

class SaveCardInitiator
{
  public static function getURL()
  {
    $userId = auth() -> user() -> id;

    $saveCardTransaction = new Transaction;

    $url = $saveCardTransaction
      -> setAmount( 20 )
      -> setUserId( $userId )
      -> shouldSaveCard()
      -> passResultingData(
        [ 
          'type'    => 'register',
          'user_id' => $userId, 
        ]
      )
      -> buildUrl();
  
    return $url;
  }
}