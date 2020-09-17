<?php

namespace App\Library\Interactors;

use Maatwebsite\Excel\Facades\Excel;
use App\Library\Entities\Exports\UsersExporter;
use App\Library\Entities\Exports\OrdersExporter;

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
}