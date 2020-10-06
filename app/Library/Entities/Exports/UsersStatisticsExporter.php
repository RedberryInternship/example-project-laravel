<?php

namespace App\Library\Entities\Exports;

use App\User;
use App\Order;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use App\Enums\OrderStatus as OrderStatusEnum;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class UsersStatisticsExporter implements FromArray, WithStyles, WithColumnWidths, WithStrictNullComparison
{
  const REGISTERED  = 'REGISTERED';
  const ACTIVE      = 'ACTIVE';
  const DEACTIVATED = 'DEACTIVATED';

  /**
   * Year of statistics.
   * 
   * @var int $year
   */
  private $year;

  /**
   * Statistics data.
   * 
   * @var array
   */
  private $data;

  /**
   * Export all user data to excel.
   */
  public function array(): array
  {
    $users = User :: all();
    $this -> initializeData();
    $this -> countRegisteredUsers($users);
    $this -> countActiveUsers();
    $this -> countDeactivatedUsers($users);

    return $this -> formatUsersStatistics();
  }

  /**
   * Apply column width.
   * 
   * @return array
   */
  public function columnWidths(): array
  {
    return [
      'A' => 12,
      'B' => 22,
      'C' => 12,
      'D' => 20,
    ];
  }

  /**
   * Set year.
   * 
   * @param int $year
   * @return self
   */
  public function setYear( $year ): self
  {
    $this -> year = $year;
    return $this;
  }

  /**
   * Month names.
   * 
   * @var array
   */
  private $monthNames = [
    0 => 'იანვარი',
    1 => 'თებერვალი',
    2 => 'მარტი',
    3 => 'აპრილი',
    4 => 'მაისი',
    5 => 'ივნისი',
    6 => 'ივლისი',
    7 => 'აგვისტო',
    8 => 'სექტემბერი',
    9 => 'ოქტომბერი',
    10 => 'ნოემბერი',
    11 => 'დეკემბერი',
  ];

  /**
   * Initialize data.
   * 
   * @return void
   */
  private function initializeData(): void
  {
    $this -> data = [ self :: REGISTERED, self :: ACTIVE, self :: DEACTIVATED ];

    for($i=0; $i<=11; $i++)
    {
      $this -> data[ self :: REGISTERED  ][$i]  = 0;
      $this -> data[ self :: ACTIVE      ][$i]  = 0;
      $this -> data[ self :: DEACTIVATED ][$i]  = 0;
    }
  }

  /**
   * Count registered users each month of year.
   * 
   * @return void
   */
  private function countRegisteredUsers( &$users ): void
  {
    foreach($users as $user)
    {
      if( $user -> created_at && $user -> created_at -> year == $this -> year)
      {
          $this -> data[ self :: REGISTERED ][ $user -> created_at -> month - 1 ]++;
      }
    }
  }

  /**
   * Count active users each month.
   * 
   * @return void
   */
  private function countActiveUsers(): void
  {
    $userIds = [];
    for($i=0; $i <= 11; $i++)
    {
      $userIds[$i] = [];
    }

    Order :: where( 'charging_status', OrderStatusEnum :: FINISHED ) -> get()
      -> filter(function($order) {
        return $order -> created_at && $order -> created_at -> year == $this -> year;
      })
      -> each(function($order) use( &$userIds ) {
        array_push($userIds[ $order -> created_at -> month - 1], $order -> user_id); 
      });


    for($i = 0; $i <= 11; $i++)
    {
      $this -> data[ self :: ACTIVE ][$i] = count(array_unique($userIds[$i]));
    }
  }

  /**
   * Count deactivated users each month of year.
   * 
   * @return void
   */
  private function countDeactivatedUsers( &$users ): void
  {
    foreach($users as $user)
    {
      if( $user -> deactivated_at && $user -> deactivated_at -> year == $this -> year)
      {
          $this -> data[ self :: DEACTIVATED ][ $user -> deactivated_at -> month - 1]++;
      }
    }
  }

  /**
   * Format user statistics.
   * 
   * @return void
   */
  private function formatUsersStatistics(): array
  {
    $data = [];
    $data [] = [ 'თვე', 'დარეგისტრირებული', 'აქტიური', 'დეაქტივირებული' ];

    for($i=0; $i <= 11; $i++)
    {
      $data []= [
       $this -> monthNames[$i],
       $this -> data[ self :: REGISTERED  ][ $i ],
       $this -> data[ self :: ACTIVE      ][ $i ],
       $this -> data[ self :: DEACTIVATED ][ $i ], 
      ];
    }

    return $data;
  }

  /**
   * Apply styles.
   * 
   * @param Worksheet
   */
  public function styles(Worksheet $sheet)
  {
    $sheet->getStyle('A1')->getFont()->setBold(true);
    $sheet->getStyle('B1')->getFont()->setBold(true);
    $sheet->getStyle('C1')->getFont()->setBold(true);
    $sheet->getStyle('D1')->getFont()->setBold(true);
  }
}