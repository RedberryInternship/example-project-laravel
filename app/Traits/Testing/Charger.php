<?php

namespace App\Traits\Testing;

use App\Facades\MockSyncer;
use App\Facades\Simulator;

use App\Charger as AppCharger;


trait Charger
{
  private $initiated;

  public function initiate_charger_transaction_with_ID_of_29()
  {
    $this -> initiated = true;

    Simulator::upAndRunning( 29 );
    Simulator::plugOffCable( 29 );
    for($i=0; $i < 10000000; $i+= 0.04); // Wait to perfectly finish disconnecting

    $new_charger        = MockSyncer::generateSingleMockCharger();
    $new_charger_id     = 29;
    $new_charger -> id  = $new_charger_id;
    
    MockSyncer::insertOrUpdateOne($new_charger);

    $charger_connector_type_id = AppCharger::with( 'connector_types' )
      -> where( 'charger_id', $new_charger_id )
      -> first()
      -> connector_types
      -> first()
      -> pivot
      -> id;
    
    $this -> withHeader( 'Authorization', 'Bearer ' . $this -> token )
          -> post($this -> uri .'charging/start', [
              'charger_connector_type_id' => $charger_connector_type_id ,
              'charging_type'             => 'FULL-CHARGE'
              ]);
  }

  public function finish_charger_transaction_with_ID_of_29()
  {
    $this -> initiated && Simulator::plugOffCable( 29 );
  }
}