<?php

namespace App\Library\Entities\Scripts;

use App\Order;
use App\Payment;

//todo Vobi,  ეს Scripts ფოდლერი ნამვილად Entities ფოლდერში უნდა იყოს?
class CacheForeignKeys {
  /**
   * Cache orders foreign keys and
   * payments foreign keys.
   *
   * @return void
   */
  public static function execute(): void
  {
    self :: cacheOrderKeys();
    self :: cachePaymentKeys();
  }

  /**
   * Cache order keys.
   *
   * @return void
   */
  private static function cacheOrderKeys(): void
  {
    Order :: with(['charger_connector_type.charger', 'kilowatt'])
      -> whereNull('old_id')
      -> get()
      -> each(function( $order ) {
        $order -> company_id         = @$order -> charger_connector_type -> charger -> company_id;
        $order -> consumed_kilowatts = @round(floatval($order -> kilowatt -> consumed), 2);
        $order -> save();
      });
  }

  /**
   * Cache payment keys.
   *
   * @return void
   */
  private static function cachePaymentKeys(): void
  {
    Payment :: with(['order.charger_connector_type.charger', 'user_card.user'])
      -> get()
      -> each( function( $payment ) {
        $payment -> company_id = @$payment -> order -> charger_connector_type -> charger -> company_id;
        $payment -> user_id    = @$payment -> user_card -> user -> id;
        $payment -> save();
      });
  }
}
