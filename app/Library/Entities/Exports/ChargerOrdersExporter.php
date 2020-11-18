<?php

namespace App\Library\Entities\Exports;

use App\Order;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Enums\OrderStatus as OrderStatusEnum;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class ChargerOrdersExporter implements FromArray, WithHeadings, WithStyles, WithColumnWidths
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
    $chargerId = $this -> ids[0];

    return Order :: with(
      [
        'charger_connector_type.charger.company',
        'kilowatt',
      ]
    )
    -> whereHas('charger_connector_type.charger', function( $query ) use( $chargerId ) {
      $query -> where('id', $chargerId );
    })
    -> where('charging_status', OrderStatusEnum :: FINISHED )
    -> orderBy( 'id', 'desc' )
    -> get()
    -> filter( function( $order ) {
      return !! $order -> charger_connector_type && $order -> getCharger();
    })
    -> map( function( $order ) {
      $charger = $order -> getCharger();

      $id                 = $order -> id;
      $chargerCode        = $charger -> code;
      $chargerDescription = $charger -> getTranslation('location','ka');
      $chargerType        = $order -> charger_connector_type -> determineChargerType();
      $consumedKilowatts  = $order -> kilowatt ? $order -> kilowatt -> consumed : '0';
      $duration           = $order -> duration;
      $company            = $charger -> company ? $charger -> company -> name : 'No Owner';        

      return [ 
        'ID'                       => $id,
        'დამტენის კოდი'            => $chargerCode,
        'დამტენის აღწერა'          => $chargerDescription,
        'დამტენის ტიპი'            => $chargerType,
        'მოხმარებული კვტ.'         => $consumedKilowatts,
        'დამუხტვის ხანგრძლივობა'   => $duration,
        'დამტენის მფლობელი'        => $company,
      ];
    })
    -> toArray();
  }

  /**
   * Apply column width.
   * 
   * @return array
   */
  public function columnWidths(): array
  {
    return [
      'A' => 7,
      'B' => 15,
      'C' => 67,
      'D' => 15,
      'E' => 20,
      'F' => 27,
      'G' => 27,
      'H' => 22,
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