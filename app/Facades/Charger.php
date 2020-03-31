<?php

namespace App\Facades;

class Charger extends Facade
{
  protected static function resolveFacade()
  {
    return resolve('charger');
  }
}