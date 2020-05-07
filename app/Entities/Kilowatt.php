<?php

namespace App\Entities;

trait Kilowatt
{
  /**
     * Set charging power.
     * 
     * @param   float|string $chargingPower
     * @return  void
     */
    public function setChargingPower($chargingPower)
    {
        $this -> charging_power = (float) $chargingPower;
        $this -> save();
    }

    /**
     * Get charging power.
     * 
     * @return float
     */
    public function getChargingPower()
    {
        return (float) $this -> charging_power;
    }
}