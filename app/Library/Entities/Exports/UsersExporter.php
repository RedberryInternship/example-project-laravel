<?php

namespace App\Library\Entities\Exports;

use App\User;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class UsersExporter implements FromArray, WithHeadings, WithColumnFormatting, WithStyles, WithColumnWidths
{
  use CommonParams;

  /**
   * IDs to be filtered with.
   * 
   * @var array $IDs
   */
  private $ids;

  /**
   * Export all user data to excel.
   */
  public function array(): array
  {
    $query = User :: with( 'user_cards' );

    if($this -> ids && ! empty( $this -> ids ))
    {
      $query -> whereIn('id', $this -> ids);
    }

    return $query
      -> get() 
      -> map( function( $user ) {

        $userDefaultCard = $user -> user_cards -> where( 'default', true ) -> first();
        $maskedPan = $userDefaultCard ? $userDefaultCard -> masked_pan : '---';

        return [
          'ID'      => $user -> id,
          'სახელი'  => $user -> first_name,
          'გვარი'   => $user -> last_name,
          'ტელეფონის ნომერი' => $user -> phone_number,
          'მეილი'   => $user -> email,
          'ბარათი'  => $maskedPan,
        ];
      })
      -> toArray();
  }

  /**
   * Format columns.
   * 
   * @return array
   */
  public function columnFormats(): array
  {
    return [
      'D' => "+#",
    ];
  }

  /**
   * Apply column width.
   * 
   * @return array
   */
  public function columnWidths(): array
  {
    return [
      'A' => 5,
      'B' => 25,
      'C' => 25,
      'D' => 25,
      'E' => 35,
      'F' => 25,
    ];
  }

  /**
   * set ids to filter users collection.
   * 
   * @param array $ids
   * @return self
   */
  public function setIDs( array $ids ): self
  {
    $this -> ids = $ids;
    return $this;
  }
}