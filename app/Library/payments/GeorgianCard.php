<?php

namespace App\Library\Payments;

use Redberry\GeorgianCardGateway\Contracts\GeorgianCardHandler;

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
    PrimaryTRXGetter :: get( $request );
  }

  /**
   * Save card with user id and
   * user card information.
   * 
   * @param   Request  $request
   * 
   * @return  void
   */
  public function update( Request $request )
  {
    $payment = new Payment;
    $payment -> update();
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