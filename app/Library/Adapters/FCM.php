<?php

namespace App\Library\Adapters;

use LaravelFCM\Facades\FCM as FCMPackage;
use LaravelFCM\Message\PayloadDataBuilder;

class FCM
{
  /**
   * Send firebase notification to single user.
   * 
   * @param string $token
   * @param array  $data
   * 
   * @return void
   */
  public static function send( string $token, $data ): void
  {
    $payload    = new PayloadDataBuilder();
    $payload    -> setData( $data );
    $data       = $payload -> build();
    FCMPackage :: sendTo( $token, null, null, $data );
  }
}