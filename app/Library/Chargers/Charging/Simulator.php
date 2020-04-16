<?php

namespace App\Library\Chargers\Charging;

use App\Exceptions\SimulatorException;
use Exception;

class Simulator extends Base 
{

    /**
     * Tell charger that it is Lvl 2 charger.
     * 
     * @param int $charger_id
     */
    public function activateSimulatorMode($charger_id)
    {
        $service_url = $this -> url . ':12801/api/simulator/cp/type/'. $charger_id . '/SIMULATOR_KEBA';
        try
        {
            $response    = $this->sendRequest($service_url);
            
            if($this -> isOk($response))
            {
                return 'Keba Simulator Activated!';
            }
            throw new Exception();   
        }
        catch(Exception $e)
        {
            return 'Keba simulator couldn\'t be activated!';
        }
    }

    /**
     * Give charger voltage.
     * 
     * @param int $charger_id
     */
    public function upAndRunning($charger_id)
    {
        $service_url = $this -> url . ':12801/api/simulator/cp/add/'. $charger_id;
       
        try
        {
            $response = $this->sendRequest($service_url);
            
            if($this -> isOk($response))
            {
                return 'Charger is up and running!';
            }
            throw new Exception();
        }
        catch(Exception $e)
        {
            return 'Charger can\'t be brought up and running!';
        }
        
    }

    /**
     * Plug connector cable of the charger.
     * 
     * @param int $charger_id
     */
    public function plugOffCable($charger_id)
    {
        $service_url = $this -> url . ':12801/api/simulator/cp/disconnect/'. $charger_id;
        
        try{
            $response = $this->sendRequest($service_url);

            if($this -> isOk($response))
            {
                return  'Charger cable is off!';
            }
            else
            {
                throw new Exception();
            }
        }
        catch(Exception $e)
        {
            return 'Charger cable can\'t be plugged off!';
        }
        
    }

    /**
     * Disconnect charger from voltage.
     * 
     * @param int $charger_id
     */
    public function shutdown($charger_id)
    {
        $service_url = $this -> url . ':12801/api/simulator/cp/remove/'. $charger_id;
        
        try
        {
            $response = $this->sendRequest($service_url);

            if($this -> isOk($response))
            {
                return 'Charger is shut down!';
            }
            throw new Exception();
        }
        catch(Exception $e)
        {
            return 'Charger can\'t be shut down!';
        }
        
    }

}