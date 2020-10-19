<?php

namespace App\Library\Entities\Business\Analytics;

use App\Library\Entities\Helper;
use App\Enums\PaymentType as PaymentTypeEnum;

class TransactionsMonthlyDataAnalyser
{
  /**
   * Analyse transactions monthly data.
   * 
   * @param $orders
   * @return array
   */
  public static function analyse($orders): array
  {
    $transactionsMonthlyData = Helper :: getFreshMonthlyData();
    $usedEnergyMonthlyData   = Helper :: getFreshMonthlyData();

    $orders -> each( function($order) use(&$transactionsMonthlyData, &$usedEnergyMonthlyData) {
        $month = $order -> created_at -> month;
        $transactionsMonthlyData[$month - 1]+= $order -> payments -> whereIn('type', [PaymentTypeEnum :: CUT, PaymentTypeEnum :: FINE ]) -> count();
        $order -> kilowatt && $usedEnergyMonthlyData[$month - 1] += $order -> kilowatt -> consumed;
    });

    $usedEnergyMonthlyData = Helper :: convertWattsToKilowatts($usedEnergyMonthlyData);
    array_walk($usedEnergyMonthlyData, function(&$el) {
      $el = round($el, 2);
    });

    return [
        'transactions' => $transactionsMonthlyData,
        'energy'       => $usedEnergyMonthlyData,
        'month_labels' => Helper :: getMonthNames(),
    ];
  }
}