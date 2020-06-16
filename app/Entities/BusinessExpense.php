<?php

namespace App\Entities;

use Cache;
use App\Order;
use Carbon\Carbon;
use App\Helpers\MonthsList;

trait BusinessExpense
{
    protected $carbonFormat = 'Y-m';

    /**
     * Generate Business Expense Query.
     * 
     * @param $monthDate
     */
    public function businessExpenseQuery($monthDate = null)
    {
        $query = Order::where('charging_status', 'FINISHED')
            -> whereHas('charger_connector_type.charger.user', function($query) {
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
     * Get Business Expense.
     * 
     * @param $monthDate
     */
    public function businessExpense($monthDate = null)
    {
        return $this -> businessExpenseQuery($monthDate) -> get();
    }

    /**
     * Get Business Expense count.
     * 
     * @param $start - Starting Date (Y-m)
     * @param $end - Ending Date (Y-m)
     */
    public function businessExpenseCount($start = null, $end = null)
    {
        $cacheKey = 'business.' . $this -> id . '.expense';

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
                $expenseSum = 0;
                foreach ($this -> businessExpense($monthDate) as $order)
                {
                    $expenseSum += $order -> expense;
                }

                return (int) $expenseSum;
            });
        }

        return $monthsData;
    }
}
