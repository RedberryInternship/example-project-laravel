<?php

namespace App\Facades;

class ChargerSyncer extends Facade{
  
  protected static function resolveFacade()
  {
    return resolve('chargerSyncer');
  }
}