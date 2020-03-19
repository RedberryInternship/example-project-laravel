<?php

namespace App\Library\Chargers;


class Simulator extends Base {


    public function activateSimulatorMode($charger_id)
    {
        $service_url = $this -> url . ':12801/api/simulator/cp/type/'. $charger_id . '/SIMULATOR_KEBA';
        dd($service_url);       
    }

    public function add($charger_id)
    {
        $service_url = $this -> url . ':12801/api/simulator/cp/add/'. $charger_id;
        dd($service_url);
    }

    public function disconnect($charger_id){
        $service_url = $this -> url . ':12801/api/simulator/cp/disconnect/'. $charger_id;
        dd($service_url);
    }

    public function remove($charger_id){
        $service_url = $this -> url . ':12801/api/simulator/cp/remove/'. $charger_id;
        dd($service_url);
    }

}