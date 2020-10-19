<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Library\Interactors\Business\Analyse;

class AnalyticsController extends Controller
{
    /**
     * Constructor for business auth middleware.
     */
    public function __construct()
    {
        $this -> middleware('business.auth');
    }

    /**
     * Get Business Charger Statuses.
     * 
     * @return \JSON
     */
    public function businessChargerStatuses()
    {
        $analysedData = Analyse :: chargerStatuses();

        return response() -> json( $analysedData );
    }

    /**
     * Get Business Income & Expense.
     * 
     * @return \JSON
     */
    public function incomeAndExpense()
    {
      $analysedData = Analyse :: incomeExpense();

      return response() -> json( $analysedData );
    }

    /**
     * TOP chargers analytics.
     * 
     * @return \JSON
     */
    public function topChargers()
    {
        $analysedData = Analyse :: topChargers();
        return response() -> json($analysedData);
    }

    /**
     * Get business Transactions & Wasted energy.
     * 
     * @return \JSON
     */
    public function businessTransactionsAndWastedEnergy()
    {
        $analysedTransactions = Analyse :: transactions();
        return response() -> json( $analysedTransactions );
    }
}
