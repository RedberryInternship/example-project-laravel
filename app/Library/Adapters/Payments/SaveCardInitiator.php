<?php

namespace App\Library\Adapters\Payments;

use Redberry\GeorgianCardGateway\Transaction;

class SaveCardInitiator
{
  public static function getURL()
  {
    $userId = auth() -> user() -> id;
    
    return Transaction :: build()
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
  }
}