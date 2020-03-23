<?php

namespace App\Library\Chargers;

use Exception;

class Simulator extends Base {

    private $response = [
        'status' => null,
        'status_code' => null,
    ];


    public function activateSimulatorMode($charger_id)
    {
        $service_url = $this -> url . ':12801/api/simulator/cp/type/'. $charger_id . '/SIMULATOR_KEBA';
        
        try{
            $response = $this->sendRequest($service_url);
            
            if($this -> isOk($response)){
                $this -> response ['status'] = 'Keba Simulator Activated!';
                $this -> response ['status_code'] = 700;
            }
            else{
                throw new Exception();
            }
        }
        catch(Exception $e){
            $this -> response ['status'] = 'Keba Simulator Couldn\'t be Activated!';
            $this -> response ['status_code'] = 707;
        }
        finally{
            return $this -> response;
        }
    }


    public function upAndRunning($charger_id)
    {
        $service_url = $this -> url . ':12801/api/simulator/cp/add/'. $charger_id;
        
        try{
            $response = $this->sendRequest($service_url);
            
            if($this -> isOk($response)){
                $this -> response ['status'] = 'Charger is Up and Running!';
                $this -> response ['status_code'] = 700;
            }
            else{
                throw new Exception();
            }
            
        }
        catch(Exception $e){
            $this -> response ['status'] = 'Charger Can\'t be brought Up and Running!';
            $this -> response ['status_code'] = 707;
        }
        finally{
            return $this -> response;
        }
    }


    public function plugOffCable($charger_id){
        $service_url = $this -> url . ':12801/api/simulator/cp/disconnect/'. $charger_id;
        
        try{
            $response = $this->sendRequest($service_url);

            if($this -> isOk($response)){
                $this -> response ['status'] = 'Charger Cable is Off!';
                $this -> response ['status_code'] = 700;
            }
            else{
                throw new Exception();
            }
        }
        catch(Exception $e){
            $this -> response ['status'] = 'Charger Cable can\'t be Plugged Off!';
            $this -> response ['status_code'] = 707;
        }
        finally{
            return $this -> response;
        }
    }


    public function shutdown($charger_id){
        $service_url = $this -> url . ':12801/api/simulator/cp/remove/'. $charger_id;
        
        try{
            $response = $this->sendRequest($service_url);
            
            if($this -> isOk($response)){
                $this -> response ['status'] = 'Charger is Shut Down!';
                $this -> response ['status_code'] = 700;
            }
            else{
                throw new Exception();
            }     
        }
        catch(Exception $e){
            $this -> response ['status'] = 'Charger can\'t be Shut Down!';
            $this -> response ['status_code'] = 707;
        }
        finally{
            return $this -> response;
        }
    }

}