<?php

namespace App\Library\Entities\Exports;

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

trait CommonParams
{
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
   * Apply styles.
   * 
   * @param Worksheet
   */
  public function styles(Worksheet $sheet)
  {
    return [
      1  => [
        'font' => [
          'bold' => true
        ]
      ],
    ];
  }
}