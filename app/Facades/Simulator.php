<?php

namespace App\Facades;

class Simulator extends Facade 
{
  protected static function resolveFacade()
  {
    return resolve('simulator');
  }

}