<?php

namespace Tests\Unit\Chargers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use App\ChargerTransaction;

use App\Traits\Testing\Charger as ChargerTrait;
use App\Traits\Testing\User as UserTrait;

class ChargingFeedback extends TestCase
{
  use RefreshDatabase,
      ChargerTrait,
      UserTrait;

  private $token;
  private $update_url;
  private $stop_url;

  protected function setUp(): void
  {
    parent::setUp();

    $this -> update_url = '/chargers/transactions/update/';
    $this -> stop_url   = '/chargers/transactions/finish/';
    $this -> token      = $this -> createUserAndReturnToken();
    $this -> uri        = config( 'app' )[ 'uri' ];
  }

  /** @test */
  public function update_charger_transaction_adds_kilowatt_record()
  {  
    $this -> initiate_charger_transaction_with_ID_of_29();
    
    $charger_transaction = ChargerTransaction::first();
    
    $this -> get( $this -> update_url . $charger_transaction -> transactionID. '/7' );
    $this -> get( $this -> update_url . $charger_transaction -> transactionID. '/14' );

    $kilowatts = $charger_transaction -> kilowatt -> consumed;
    
    $this -> assertCount( 3, $kilowatts );

    $this -> finish_charger_transaction_with_ID_of_29();
  }

  /** @test */
  public function charger_transaction_status_becomes_FINISHED_when_finished()
  {
    $this -> initiate_charger_transaction_with_ID_of_29();
    
    $charger_transaction = ChargerTransaction::first();

    $this -> get( $this -> stop_url . $charger_transaction -> transactionID );

    $new_charger_transaction_status = ChargerTransaction::first() -> status;
    
    $this -> assertEquals( 'FINISHED', $new_charger_transaction_status );
    
    $this -> finish_charger_transaction_with_ID_of_29();
  }

}