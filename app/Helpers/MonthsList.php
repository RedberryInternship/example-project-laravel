<?php

namespace App\Helpers;

use Carbon\CarbonPeriod;

class MonthsList
{
    public static function get($start, $end)
    {
        $months = [];
        foreach (CarbonPeriod::create($start, '1 month', $end) as $month)
        {
            $months[] = $month -> format('Y-m');
        }

        return $months;
    }
}
