<?php

namespace App\Library\Chargers;

use Exception;

class Charger extends Base
{
    
    private $response = [
        'status_code' => null,
        'data' => null,
    ];


    /**
     * Get all the chargers info from Misha's back
     * 
     * @return array
     */
    public function all()
    {
        $service_url = $this -> url . '/es-services/mobile/ws/chargers';
        return $this -> fetchData($service_url);
    }


    /**
     * Get All the active chargers charger_id
     * 
     * @return array
     */
    public function getFreeChargersIds()
    {    
        $free_chargers_ids = [];
        $all_chargers_info = $this -> all();

        if($all_chargers_info['status_code'] == 700)
        {
            $all_chargers_info = $all_chargers_info['data'] -> data -> chargers;    
            foreach($all_chargers_info as $single_charger_info)
            {
                if($single_charger_info -> status == 0)
                {
                    $free_chargers_ids []= $single_charger_info -> id;
                }
            }
        }
        return $free_chargers_ids;
    }

    public function find($charger_id)
    {
        $service_url = $this -> url 
                        . '/es-services/mobile/ws/charger/info/' 
                        . $charger_id;
        
        return $this -> fetchData($service_url);        
     }

     /**
      * Find out if this specific charger is free
      * 
      * @param int $charger_id
      * @return bool 
      */
      public function isChargerFree($charger_id)
      {
        $result = $this -> find($charger_id);
        if($result['status_code'] == 700)
        {
            return $result['data'] -> data -> status == 0;
        }

        return false;
      }
    
     public function start($charger_id, $connector_id)
     {
        $service_url = $this -> url 
                        . '/es-services/mobile/ws/charger/start/'
                        . $charger_id .'/' 
                        . $connector_id;

        return $this -> fetchData($service_url);
    }
    
    public function stop($charger_id, $transaction_id)
    {
        $service_url = $this -> url 
                        . '/es-services/mobile/ws/charger/stop/'
                        . $charger_id .'/' 
                        . $transaction_id;
        
        return $this -> fetchData($service_url);
    }

    public function transactionInfo($id)
    {
        $service_url = $this -> url 
                        . '/es-services/mobile/ws/transaction/info/'
                        . $id;
        
        return $this -> fetchData($service_url);
    }
  
    
    private function fetchData($service_url)
    {
        try{
            $response = $this -> sendRequest($service_url);
    
            if($this -> isOk($response))
            {
                $this -> setResponse(700, json_decode($response['body']));
            }
            else
            {
                throw new Exception();
            }
        }
        catch(Exception $e)
        {
            $this -> setResponse(707);
        }
        finally
        {
            return $this -> response;
        }
    }

    private function setResponse( $code, $data = null)
    {
        $this -> response ['status_code'] = $code;
        $this -> response ['data'] = $data; 
    }


}
