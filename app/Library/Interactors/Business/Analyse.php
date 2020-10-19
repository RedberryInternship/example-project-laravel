<?php

namespace App\Library\Interactors\Business;

use App\Library\Entities\Business\Analytics\TopChargersAnalyser;
use App\Library\Entities\Business\Analytics\BusinessOrdersGetter;
use App\Library\Entities\Business\Analytics\ChargerStatusAnalyser;
use App\Library\Entities\Business\Analytics\IncomeExpenseAnalyser;
use App\Library\Entities\Business\Analytics\TransactionsMonthlyDataAnalyser;

class Analyse
{
  /**
   * Analyse transactions data.
   * 
   * @return \array
   */
  public static function transactions(): array
  {
    $orders = BusinessOrdersGetter :: get();
    return TransactionsMonthlyDataAnalyser :: analyse($orders);
  }

  /**
   * Analyse charger statuses.
   * 
   * @return \array
   */
  public static function chargerStatuses(): array
  {
    return ChargerStatusAnalyser :: analyse();
  }

  /**
   * Analyse business income and expense.
   * 
   * @return array
   */
  public static function incomeExpense(): array
  {
    return IncomeExpenseAnalyser :: analyse();
  }

  /**
   * Analyse top chargers for business.
   * 
   * @return array
   */
  public static function topChargers(): array
  {
    return TopChargersAnalyser :: analyse();
  }
}