<?php

namespace App\Library\Interactors;

use Maatwebsite\Excel\Facades\Excel;
use App\Library\Entities\Exports\UsersExporter;
use App\Library\Entities\Exports\OrdersExporter;
use App\Library\Entities\Exports\BusinessOrdersExporter;
use App\Library\Entities\Exports\UsersStatisticsExporter;

class Exporter
{
  /**
   * Export users to excel.
   * 
   * @return \File
   */
  public static function exportUsers()
  {
    return Excel :: download( new UsersExporter, 'users.xlsx' );
  }

  /**
   * Export orders.
   * 
   * @return \File
   */
  public static function exportOrders()
  {
    return Excel :: download( new OrdersExporter, 'orders.xlsx' );
  }

  /**
   * Export users statistics.
   * 
   * @param int $year
   * @return \File
   */
  public static function exportUsersStatistics()
  {
    return Excel :: download(new UsersStatisticsExporter, 'users-statistics.xlsx');
  }

  /**
   * Export business orders.
   * 
   * @return \File
   */
  public static function exportBusinessOrders()
  {
    return Excel :: download(new BusinessOrdersExporter, 'business-orders.xlsx');
  }
}