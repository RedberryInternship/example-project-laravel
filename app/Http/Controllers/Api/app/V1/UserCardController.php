<?php

namespace App\Http\Controllers\Api\app\V1;

use App\Http\Controllers\Controller;

use App\Library\Payments\SaveCardInitiator;

use App\User;

use App\Http\Requests\SetDefaultUserCardRequest;

class UserCardController extends Controller
{
  /**
   * Get georgian card url for saving user card.
   * 
   * @return JSON
   */
  public function getSaveCardUrl()
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

  /**
   * Set default user card.
   * 
   * @return JSON
   */
  public function setDefaultUserCard( SetDefaultUserCardRequest $request )
  {
    $userCardId       = $request -> get( 'user_card_id' );
    $userId           = auth()   -> user() -> id;
    $user             = User :: with( 'user_cards' ) -> find( $userId );
    $selectedUserCard = $user -> user_cards() -> find( $userCardId );

    $user -> user_cards() -> update([ 'default' => false ]);

    $selectedUserCard -> update([ 'default' => true ]);

    return response() -> json([ 'success' => true ]);
  }
}