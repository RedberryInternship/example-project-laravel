<?php

namespace App\Library\Entities\Business\Analytics;

use App\Library\Entities\Helper;

class IncomeExpenseAnalyser
{
  /**
   * Analyse business expense and income.
   * 
   * @return array
   */
  public static function analyse( $orders ): array
  {
    return [
      'income'  => self :: countIncome( $orders ),
      'expense' => self :: countExpense( $orders ),
      'month_labels' => Helper :: getMonthNames(),
    ];
  }

  /**
   * Count income.
   * 
   * @return array
   */
  private static function countIncome( $orders ): array 
  {
      $freshMonthlyData = Helper :: getFreshMonthlyData();

      $orders -> each(function( $order ) use( &$freshMonthlyData ) {
        $month = $order -> created_at -> month - 1;

        $penaltyFee = $order -> penalty_fee ?? 0;
        $chargePrice = $order -> charge_price ?? 0;

        $freshMonthlyData[$month] += ($penaltyFee + $chargePrice);
      });

      array_walk($freshMonthlyData, function( &$el ) {
        $el = round($el, 2);
      });

      return $freshMonthlyData;
  }
    
  /**
   * Count income.
   * 
   * @return array
   */
  private static function countExpense( $orders ): array 
  {
      $freshMonthlyData = Helper :: getFreshMonthlyData();

      $orders -> each(function( $order ) use( &$freshMonthlyData ) {
        $month = $order -> created_at -> month - 1;

        $expense = $order -> consumed_kilowatts * 0.18;
        $freshMonthlyData[$month] += $expense;
      });

      array_walk($freshMonthlyData, function( &$el ) {
        $el = round($el, 2);
      });

      return $freshMonthlyData;
  }
}