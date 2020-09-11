<?php

namespace App\Library\Entities\Exports;

use App\User;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class UsersExporter implements FromArray, WithHeadings, WithColumnFormatting
{
  /**
   * Export all user data to excel.
   */
  public function array(): array
  {
    return dd(User :: all() -> toArray());
  }

  /**
   * Heading.
   * 
   * @return array
   */
  public function headings(): array 
  {
    return array_keys($this -> array()[0]);
  }

  /**
   * Format columns.
   * 
   * @return array
   */
  public function columnFormats(): array
  {
    return [
      'D' => NumberFormat :: FORMAT_TEXT,
    ];
  }
}