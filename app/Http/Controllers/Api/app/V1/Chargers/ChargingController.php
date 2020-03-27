<?php


namespace App\Http\Controllers\Api\App\V1\Chargers;
use App\Http\Controllers\Controller;
use App\Http\Requests\StartCharging;
class ChargingController extends Controller{

  private $status_code;

  public function __construct(){
    $this -> status_code = 200;
  }


  public function start(StartCharging $request){

    return response() -> json("Ok");
    
  }


  public function stop(){

  }

}