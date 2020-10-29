<?php

use Illuminate\Support\Facades\Route;
use App\Library\Interactors\Nova\ChargerTerminals;
use App\Library\DataStructures\ChargerTerminals as ChargerTerminalsRequest;

/*
|--------------------------------------------------------------------------
| Tool API Routes
|--------------------------------------------------------------------------
|
| Here is where you may register API routes for your tool. These routes
| are loaded by the ServiceProvider of your tool. They are protected
| by your tool's "Authorize" middleware by default. Now, go build!
|
*/

//todo Vobi, ნოვაში კიდევ რატომ გვაქვს დამატებით ეს routes. api.php? რა პრინციპით ვყოფთ ამას?
// და რატომ არ დავწერეთ ეს მაგალითად root route ფოლდერში?
Route :: get( 'chargers' , function() { return ChargerTerminals :: getChargers();  });
Route :: get( 'terminals', function() { return ChargerTerminals :: getTerminals(); });

Route :: post( 'save'     , function() {
  $terminalId = request() -> get( 'terminal_id' );
  $chargerId  = request() -> get( 'charger_id'  );
  $report     = request() -> get( 'report'      );

  $req = ChargerTerminalsRequest :: instance()
    -> setTerminalId( $terminalId )
    -> setChargerId ( $chargerId  )
    -> setReport    ( $report     );

  return ChargerTerminals :: save( $req );
});
