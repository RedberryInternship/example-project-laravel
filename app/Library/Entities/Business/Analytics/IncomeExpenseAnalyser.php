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
      $year = request() -> year ?? now() -> year;

      return cache()
          -> remember(
              "business.income-expense.{$year}", 
              60 * 60, /* 1 Hour */
              function() use( $orders ) {
                return self :: calculateIncomeExpense( $orders );
              },
      );
  }

  /**
   * Calculate income expense data.
   *
   * @return array
   */
  private static function calculateIncomeExpense( $orders ): array
  {
    return [
      'income_without_penalty'  => self :: countIncomeWithoutPenalty( $orders ),
      'penalty'                 => self :: countPenalty( $orders ),
      'expense'                 => self :: countExpense( $orders ),
      'month_labels'            => Helper :: getMonthNames(),
    ];
  }

  /**
   * Count income without penalty prices.
   * 
   * @return array
   */
  private static function countIncomeWithoutPenalty( $orders ): array 
  {
      $freshMonthlyData = Helper :: getFreshMonthlyData();

      $orders -> each(function( $order ) use( &$freshMonthlyData ) {
        $month        = $order -> created_at -> month - 1;
        $chargePrice  = $order -> charge_price ?? 0;
        
        $freshMonthlyData[$month] += $chargePrice;
      });

      array_walk($freshMonthlyData, function( &$el ) {
        $el = round($el, 2);
      });

      return $freshMonthlyData;
  }
  
  /**
   * Count penalty prices.
   * 
   * @return array
   */
  private static function countPenalty( $orders ): array 
  {
      $freshMonthlyData = Helper :: getFreshMonthlyData();

      $orders -> each(function( $order ) use( &$freshMonthlyData ) {
        $month        = $order -> created_at -> month - 1;
        $penaltyFee   = $order -> penalty_fee ?? 0;

        $freshMonthlyData[$month] += $penaltyFee;
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
        $kilowattPrice = $order -> charger_connector_type -> charger -> kilowatt_price;

        $expense = $order -> consumed_kilowatts * $kilowattPrice;
        $freshMonthlyData[$month] += $expense;
      });

      array_walk($freshMonthlyData, function( &$el ) {
        $el = round($el, 2);
      });

      return $freshMonthlyData;
  }
}
