<?php

namespace App\Library\Chargers;

use Exception;

class Charger extends Base
{
    
    public function all(){
        $service_url = $this -> url . '/es-services/mobile/ws/chargers';
        return $this -> sendRequest($service_url);
    }

    public function find($charger_id)
    {

        if (isset($charger_id) && $charger_id)
        {
            $service_url = $this -> url . '/es-services/mobile/ws/charger/info/' . $charger_id;
            return $this -> sendRequest($service_url);
        }
    }

    public function start($charger_id, $connector_id){
        $service_url = $this -> url . '/es-services/mobile/ws/charger/start/'. $charger_id .'/' . $connector_id;
        dd($service_url);
    }

    public function stop($charger_id, $transaction){
        $service_url = $this -> url . '/es-services/mobile/ws/charger/stop/'. $charger_id .'/' . $transaction;
        dd($service_url);
    }

    public function transactionInfo($id){
        $service_url = $this -> url . '/es-services/mobile/ws/transaction/info/'. $id;
        dd($service_url);
    }


}
