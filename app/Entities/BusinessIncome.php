<?php

namespace App\Entities;

use Cache;
use App\Payment;
use Carbon\Carbon;
use App\Helpers\MonthsList;

trait BusinessIncome
{
    protected $carbonFormat = 'Y-m';

    /**
     * Generate Business Income Query.
     * 
     * @param $monthDate
     */
    public function businessIncomeQuery($monthDate = null)
    {
        $query = Payment::where(function($q) {
            $q -> where('type', 'CUT')
               -> orWhere('type', 'FINE');
        })
        -> whereHas('order.charger_connector_type.charger.user', function($query) {
            $query -> where('users.id', $this -> id);
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
     * Get Business Income.
     * 
     * @param $monthDate
     */
    public function businessIncome($monthDate = null)
    {
        return $this -> businessIncomeQuery($monthDate) -> get();
    }

    /**
     * Get Business Income count.
     * 
     * @param $start - Starting Date (Y-m)
     * @param $end - Ending Date (Y-m)
     */
    public function businessIncomeCount($start = null, $end = null)
    {
        $cacheKey = 'business.' . $this -> id . '.income';

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
                $priceSum = 0;
                foreach ($this -> businessIncome($monthDate) as $payment)
                {
                    $priceSum += $payment -> price;
                }

                return (int) $priceSum;
            });
        }

        return $monthsData;
    }
}
