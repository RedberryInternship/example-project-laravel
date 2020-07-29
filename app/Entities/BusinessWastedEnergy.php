<?php

namespace App\Entities;

use Cache;
use App\Kilowatt;
use Carbon\Carbon;
use App\Helpers\MonthsList;

trait BusinessWastedEnergy
{
    protected $carbonFormat = 'Y-m';

    /**
     * Generate Business Wasted Energy Query.
     * 
     * @param $monthDate (Y-m)
     */
    public function businessWastedEnergyQuery($monthDate = null)
    {
        $query = Kilowatt::whereHas('order.charger_connector_type.charger', function($query) {
            $query -> where('chargers.company_id', $this -> company_id);
        });

        if ($monthDate)
        {
            $query
                -> whereYear('created_at', '=', explode('-', $monthDate)[0])
                -> whereMonth('created_at', '=', explode('-', $monthDate)[1]);
        }

        return $query;
    }

    /**
     * Get Business Wasted Energy.
     * 
     * @param $monthDate (Y-m)
     */
    public function businessWastedEnergy($monthDate)
    {
        return $this -> businessWastedEnergyQuery($monthDate) -> get();
    }

    /**
     * Get Business Wasted Energy count.
     * 
     * @param $start - Starting Date (Y-m)
     * @param $end - Ending Date (Y-m)
     */
    public function businessWastedEnergyCount($start = null, $end = null)
    {
        $cacheKey = 'business.' . $this -> id . '.wastedEnergyCount';

        if ( ! $start)
        {
            $start = $this -> created_at -> format($this -> carbonFormat);
        }

        if ( ! $end)
        {
            $end = Carbon::now() -> format($this -> carbonFormat);
        }

        $monthsData = [];
        foreach (MonthsList::get($start, $end) as $monthDate)
        {
            $monthsData[$monthDate] = Cache::remember($cacheKey . '.' . $monthDate, 20, function () use ($monthDate) {
                $kwts = $this -> businessWastedEnergy($monthDate);

                $kwtsCount = 0;
                foreach ($kwts as $kwt)
                {
                    $kwtsCount += $kwt -> consumed;
                }

                return $kwtsCount;
            });
        }

        return $monthsData;
    }
}
