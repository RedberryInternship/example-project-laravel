<?php

namespace App\Library\Interactors\CronJobs;

use App\Order;
use App\ChargingPower;
class UnhandledChargingPowerChecker
{
    /**
     * Check all the orders unhandled charging powers.
     */
    public static function checkAll()
    {
        $activeOrders = Order :: query()
            -> active()
            -> with('charging_powers')
            -> get();
        
        $activeOrders->each(function($order) {
            self::check($order);
        });
    }

    /**
     * Check single order unhandled charging power.
     */
    public static function check(Order $order, $hasFinished = false)
    {
        $chargingPowers = $order->charging_powers->toArray();

        if($hasFinished === true)
        {
            [ 'end_at' => $latestChargingPowerEndTimestamp ] = end($chargingPowers);
            [ 'id' => $latestChargingPowerId ] = end($chargingPowers);

            if($latestChargingPowerEndTimestamp === null)
            {
                ChargingPower::whereId($latestChargingPowerId)->update(['end_at' => now()->timestamp]);
            }
        }

        for($i=0; $i < count($chargingPowers) - 1; $i++)
        {
            ['id' => $id, 'end_at' => $endAt] = $chargingPowers[$i];
            if($endAt === null)
            {
                ['start_at' => $previousStartedAt] = $chargingPowers[$i + 1];

                ChargingPower :: query()
                    -> whereId($id)
                    -> update([ 'end_at' => $previousStartedAt]);
            }
        } 
    }
}