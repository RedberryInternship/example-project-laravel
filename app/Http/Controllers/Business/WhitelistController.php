<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;

use App\Http\Requests\Business\RemoveFromWhitelist;
use App\Http\Requests\Business\ToggleHiddenField;
use App\Http\Requests\Business\AddToWhitelist;

use App\Charger;

class WhitelistController extends Controller
{
  /**
   * Toggle charger hidden field.
   * 
   * @return View
   */
  public function toggleHiddenField( ToggleHiddenField $request )
  {
    $chargerId  = request() -> charger_id;
    $hidden     = request() -> hidden;

    Charger :: whereId( $chargerId ) -> update([ 'hidden' => $hidden ]);

    return redirect() -> route('chargers.edit', [ $chargerId ]);
  }

  /**
   * Get whitelist records.
   * 
   * @return JSON
   */
  public function getWhitelist()
  {
    //
  }

  /**
   * Add into whitelist.
   * 
   * @return JSON
   */
  public function addToWhitelist( AddToWhitelist $request )
  {
    //
  }

  /**
   * Remove from whitelist.
   * 
   * @return JSON
   */
  public function removeFromWhitelist( RemoveFromWhitelist $request )
  {
    //
  }
}
