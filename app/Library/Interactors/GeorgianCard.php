<?php

namespace App\Library\Interactors;

use Redberry\GeorgianCardGateway\Contracts\GeorgianCardHandler;

use App\Library\Entities\GeorgianCard\PrimaryTRXGetter;
use App\Library\Entities\GeorgianCard\SaveCardRefunder;
use App\Library\Entities\GeorgianCard\UserCardSaver;
use App\Library\Entities\GeorgianCard\Payer;

use Illuminate\Http\Request;

class GeorgianCard implements GeorgianCardHandler
{
  /**
   * Get primary transaction id
   * for recurrent transactions.
   * 
   * @param   Request $request
   * @return  string|null
   */
  public function getPrimaryTransactionId( Request $request )
  {
    return PrimaryTRXGetter :: get( $request );
  }

  /**
   * Determine if it should save card or pay
   * and proceed accordingly.
   * 
   * @param   Request  $request
   * 
   * @return  void
   */
  public function update( Request $request )
  {
    if( UserCardSaver :: shouldSaveUserCard() )
    {
      UserCardSaver :: save();
    }
    else
    {
      Payer :: pay();
    }
  }

  /**
   * Success method will be executed if
   * transaction is to end successfully.
   * 
   * @return mixed
   */
  public function success()
  {
    SaveCardRefunder :: RefundIfCardSaved();

    dump( 'Success' );
  }

  /**
   * Failed method will be executed if
   * transaction is to end with failure.
   * 
   * @return mixed
   */
  public function failure()
  {
    dump( 'Failure' );
  }
}