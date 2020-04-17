<?php

namespace App\Facades;

class MockSyncer extends Facade
{
  protected static function resolveFacade()
  {
    return resolve('mockSyncer');
  }
}