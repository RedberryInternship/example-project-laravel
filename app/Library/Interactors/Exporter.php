<?php

namespace App\Library\Interactors;

use Maatwebsite\Excel\Facades\Excel;
use App\Library\Entities\Exports\UsersExporter;

class Exporter
{
  /**
   * Export users to excel.
   * 
   * @return \File
   */
  public static function exportUsers()
  {
    return Excel :: download( new UsersExporter, 'users.xlsx');
  }
}