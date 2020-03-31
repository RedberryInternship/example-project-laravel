<?php

namespace App\Library\Chargers;

use Exception;

class Charger extends Base
{
    
    private $response = [
        'status_code' => null,
        'data' => null,
    ];



    public function all(){
        $service_url = $this -> url . '/es-services/mobile/ws/chargers';
        return $this -> fetchData($service_url);
    }

    public function find($charger_id)
    {
        $service_url = $this -> url 
                        . '/es-services/mobile/ws/charger/info/' 
                        . $charger_id;
        
        return $this -> fetchData($service_url);        
     }
    
     public function start($charger_id, $connector_id){
        $service_url = $this -> url 
                        . '/es-services/mobile/ws/charger/start/'
                        . $charger_id .'/' 
                        . $connector_id;

        return $this -> fetchData($service_url);
    }
    
    public function stop($charger_id, $transaction_id){
        $service_url = $this -> url 
                        . '/es-services/mobile/ws/charger/stop/'
                        . $charger_id .'/' 
                        . $transaction_id;
        
        return $this -> fetchData($service_url);
    }

    public function transactionInfo($id){
        $service_url = $this -> url 
                        . '/es-services/mobile/ws/transaction/info/'
                        . $id;
        
        return $this -> fetchData($service_url);
    }
  
    
    private function fetchData($service_url){
        try{
            $response = $this -> sendRequest($service_url);
    
            if($this -> isOk($response)){
                $this -> setResponse(700, json_decode($response['body']));
            }
            else{
                throw new Exception();
            }
        }
        catch(Exception $e){
            $this -> setResponse(707);
        }
        finally{
            return $this -> response;
        }
    }

    private function setResponse( $code, $data = null){
        $this -> response ['status_code'] = $code;
        $this -> response ['data'] = $data; 
    }


}
