<?php

namespace App\Http\Controllers\Api\app\V1;

use App\Http\Controllers\Controller;

use App\Library\Payments\SaveCardInitiator;

class SaveCardController extends Controller
{
  public function __invoke()
  {
    $url = SaveCardInitiator :: getURL();
    
    return response() -> json(
      [
        'save_card_url' => $url,
      ]
    );
  }
}