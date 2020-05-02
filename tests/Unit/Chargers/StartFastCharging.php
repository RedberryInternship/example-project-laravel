<?php

namespace Tests\Unit\Chargers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

use App\Enums\ChargingType as ChargingTypeEnum;
use App\Enums\OrderStatus as OrderStatusEnum;

use App\ChargerConnectorType;
use App\Kilowatt;
use App\Order;

use App\Traits\Testing\Charger as ChargerTrait;
use App\Traits\Testing\User as UserTrait;
use App\Traits\Message;


class StartFastCharging extends TestCase {
  
  use RefreshDatabase,
      UserTrait,
      ChargerTrait,
      Message;

  private $token;
  private $uri;
  private $url;

  protected function setUp(): void
  {
    parent::setUp();

    $this -> token  = $this -> createUserAndReturnToken();
    $this -> uri    = config( 'app' )['uri'];
    $this -> url    = $this -> uri . 'charging/start';
  }

  protected function tearDown(): void
  {
    $this -> beforeApplicationDestroyed( function () {
      foreach(DB::getConnections() as $connection )
      {
        $connection -> disconnect();
      }
    });
    parent :: tearDown();
  } 
}
