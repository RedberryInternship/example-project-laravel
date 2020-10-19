<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Facades\Simulator;
use App\Traits\Message;

use Redberry\GeorgianCardGateway\Refund;

class TestController extends Controller 
{
  use Message;
    
  public function __invoke()
  {
    dd( "nothingness" );
  }

  public function refundView()
  {
    return view('refund');
  }

  public function doRefund()
  {
    $rrn = request() -> rrn;
    $trxId = request() -> trxId;
    $amount = request() -> amount;

    $url = Refund :: build()
    -> setTrxId( $trxId )
    -> setRRN( $rrn )
    -> setAmount( $amount )
    -> buildUrl();

    return redirect( $url );
  }

  public function firebase()
  {
    return view( 'firebase' );
  }

  public function disconnect( Request $request )
  { 
    if( request() -> has( 'chargerCode' ) )
    {
      $chargerId    = '0000';
      $chargerCode  = request() -> get( 'chargerCode' );
      $charger      = DB :: table( 'chargers' ) -> where( 'code', $chargerCode ) -> first();

      if( $charger )
      {
        $chargerId = $charger -> charger_id;
      }

      return response() -> json(
        Simulator :: plugOffCable( $chargerId ),
      );
    }

    return view('simulator.disconnect');
  }
}