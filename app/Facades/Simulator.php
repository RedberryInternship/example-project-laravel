<?php

namespace App\Facades;

use RuntimeException;

class Simulator extends Facade 
{
  protected static function resolveFacade()
  {
    return resolve('simulator');
  }

}