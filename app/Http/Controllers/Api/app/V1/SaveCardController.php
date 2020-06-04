<?php

namespace App\Http\Controllers\Api\app\V1;

use App\Http\Controllers\Controller;

use Redberry\GeorgianCardGateway\Transaction;

class SaveCardController extends Controller
{
  public function __invoke()
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

    return response() -> json(
      [
        'save_card_url' => $url,
      ]
    );
  }
}