<?php

namespace App\Helpers;

class App
{
  /**
   * Determine if application is in development mode.
   * 
   * @return bool
   */
  public static function dev(): bool
  {
    return config( 'app.env' ) !== 'production';
  }
}