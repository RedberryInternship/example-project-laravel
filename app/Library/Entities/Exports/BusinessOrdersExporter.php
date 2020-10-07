<?php

namespace App\Library\Entities\Exports;

use App\User;
use App\Order;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use App\Library\Entities\ChargingProcess\Timestamp;

class BusinessOrdersExporter implements FromArray, WithHeadings, WithStyles, WithColumnWidths
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

    $user = User :: with('company') -> find(auth()->user()->id);

    $query = Order :: with(
      [
        'charger_connector_type.charger.company',
        'kilowatt',
      ]
    )
    -> whereHas('charger_connector_type.charger', function($query) use($user) {
      $query -> where('company_id', $user -> company -> id );
    })
    -> orderBy('id', 'desc');

    return $query
      -> get()
      -> filter( function( $order ) {
        return !! $order -> charger_connector_type && $order -> charger_connector_type -> charger;
      })
      -> map( function( $order ) {
        $timestamp = Timestamp :: build($order);
        $charger = $order -> charger_connector_type -> charger;

        $id                 = $order -> id;
        $chargerCode        = $charger -> code;
        $chargerDescription = $charger -> getTranslation('location','ka');
        $chargerType        = $order -> charger_connector_type -> determineChargerType();
        $consumedKilowatts  = $order -> kilowatt ? $order -> kilowatt -> consumed : '0';
        $duration           = $order -> duration;
        $startTime          = $timestamp -> getStartTimestamp();
        $endTime            = $timestamp -> getOriginalEndTime();
        $chargeTime         = $timestamp -> getStopChargingTimestamp() ?? $endTime;
        $chargePower        = $order -> charge_power ? $order -> charge_power : '0';
        $chargePrice        = $order -> charge_price;
        $penaltyFee         = $order -> penalty_fee ? $order -> penalty_fee : '0' ;
        $company            = $charger -> company ? $charger -> company -> name : 'No Owner';        

        return [ 
          'ID'                       => $id,
          'დამტენის კოდი'            => $chargerCode,
          'დამტენის აღწერა'          => $chargerDescription,
          'დამტენის ტიპი'            => $chargerType,
          'მოხმარებული კვტ.'         => $consumedKilowatts,
          'დამუხტვის სიმძლავრე'      => $chargePower,
          'დამუხტვის ხანგრძლივობა'   => $duration,
          'დამუხტვის დაწყების დრო'   => $startTime,
          'დამუხტვის შეჩერების დრო'  => $chargeTime,
          'დამუხტვის დასრულების დრო' => $endTime,
          'ტრანზაქციის ღირებულება'   => $chargePrice,
          'საჯარიმო გადასახადი'      => $penaltyFee,
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
      'F' => 25,
      'G' => 27,
      'H' => 25,
      'I' => 27,
      'J' => 28,
      'K' => 25,
      'L' => 22,
      'M' => 22,
    ];
  }
}