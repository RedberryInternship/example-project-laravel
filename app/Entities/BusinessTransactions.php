<?php

namespace App\Entities;

use Cache;
use App\Payment;
use Carbon\Carbon;
use App\Helpers\MonthsList;

trait BusinessTransactions
{
    protected $carbonFormat = 'Y-m';

    /**
     * Generate Business Transactions Query.
     * 
     * @param $monthDate
     */
    public function businessTransactionsQuery($monthDate = null)
    {
        $query = Payment::whereHas('order.charger_connector_type.charger.user', function($query) {
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
     * Get Business Transactions.
     */
    public function businessTransactions()
    {
        return $this -> businessTransactionsQuery() -> get();
    }

    /**
     * Get Business Transactions count.
     * 
     * @param $start - Starting Date (Y-m)
     * @param $end - Ending Date (Y-m)
     */
    public function businessTransactionsCount($start = null, $end = null)
    {
        $cacheKey = 'business.' . $this -> id . '.transactionsCount';

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
            $monthsData[$monthDate] = Cache::get($cacheKey . '.' . $monthDate, function () use ($monthDate) {
                return $this -> businessTransactionsQuery($monthDate) -> count();
            });
        }

        return $monthsData;
    }
}
