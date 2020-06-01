<?php

namespace App\Library;

use App\Order;

class GeorgianCardHandler
{
  public function getPrimaryTransactionId( $order_id )
  {
    $order = Order :: with( 'user_card' ) -> find( $order_id );
    
    if( $order && $order -> user_card && $order -> user_card -> transaction_id )
    {
      return $order -> user_card -> transaction_id;
    }
  }

  public function registerPrimaryTransactionId( $orderInfo, $userCardInfo )
  {
    $order = Order :: with( 'user_card' ) -> find( $orderInfo -> order_id );

    if( $order && ! $order -> user_card )
    {
       $order -> user_card() -> create(
         [
           'user_id'        => $order -> user_id,
           'masked_pan'     => $userCardInfo -> masked_pan,
           'transaction_id' => $orderInfo -> transaction_id,
           'card_holder'    => $userCardInfo -> card_holder,
           'default'        => true,
           'active'         => true,
         ]
       );
    }
  }

  public function success()
  {
    //
  }

  public function failed()
  {
    //
  }
}