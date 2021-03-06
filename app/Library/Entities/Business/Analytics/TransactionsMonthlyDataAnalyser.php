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
      $year = request() -> year ?? now() -> year;

      $data = cache()
          -> remember(
              "business.transactions-data-with-energy-waste.{$year}", 
              60 * 60, /* 1 Hour */
              function () use( $orders ) {
                return self :: calculateTransactionsDataWithEnergyWaste( $orders );
              },
      );

      $data['month_labels'] = Helper :: getMonthNames();

      return $data;
  }

  /**
   * Calculate monthly transactions 
   * and energy waste data.
   *
   * @return array
   */
  private static function calculateTransactionsDataWithEnergyWaste( $orders ): array
  {
    $transactionsMonthlyData = Helper :: getFreshMonthlyData();
    $usedEnergyMonthlyData   = Helper :: getFreshMonthlyData();

    $orders -> each( function($order) use(&$transactionsMonthlyData, &$usedEnergyMonthlyData) {
        $month = $order -> created_at -> month;
        $transactionsMonthlyData[$month - 1]+= $order -> payments -> whereIn('type', [PaymentTypeEnum :: CUT, PaymentTypeEnum :: FINE ]) -> count();
        $order -> consumed_kilowatts && $usedEnergyMonthlyData[$month - 1] += $order -> consumed_kilowatts;
    });

    $usedEnergyMonthlyData = Helper :: convertWattsToKilowatts($usedEnergyMonthlyData);
    array_walk($usedEnergyMonthlyData, function(&$el) {
      $el = round($el, 2);
    });

    return [
        'transactions' => $transactionsMonthlyData,
        'energy'       => $usedEnergyMonthlyData,
    ];
  }
}
