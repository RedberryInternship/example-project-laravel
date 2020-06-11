<?php

namespace App\Http\Controllers\Api\app\V1;

use App\Http\Controllers\Controller;

use App\Library\Payments\SaveCardInitiator;

class SaveCardController extends Controller
{
  public function __invoke()
  {
    $url        = SaveCardInitiator :: getURL();
    $failedUrl  = config( 'georgian-card-gateway' )[ 'back_url_f' ];
    $successUrl = config( 'georgian-card-gateway' )[ 'back_url_s' ];

    return response() -> json(
      [
        'save_card_url' => $url,
        'success_url'   => $successUrl,
        'failed_url'    => $failedUrl,
      ]
    );
  }
}