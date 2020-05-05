<?php


namespace App\Http\Controllers\Api\ChargerTransactions\V1;

use App\Http\Controllers\Controller;

use App\Enums\ChargerType as ChargerTypeEnum;
use App\Enums\OrderStatus as OrderStatusEnum;
use App\Enums\PaymentType as PaymentTypeEnum;

use App\Library\Payments\Payment;
use Carbon\Carbon;
use App\Config;
use App\Order;

class TransactionController extends Controller
{
  /**
   * Order instance.
   */
  private $order;

  /**
   * KiloWattHour line with which we're gonna
   * determine if charging is officially started
   * and if charging is officially ended.
   */
  private $kiloWattHourLine = 2;

  /**
   * Update charging info. what is the 
   * current kilowatt value.
   * 
   * @param string $transaction_id
   * @param int $value
   * @return void
   */
  public function update( $transaction_id, $value )
  {
    $this -> order = Order :: with(
        [
          'charger_connector_type',
          'payments',
          'user',
        ]
      ) 
      -> where( 'charger_transaction_id', $transaction_id ) 
      -> first();

    $this -> order -> addKilowatt( $value );

    $this -> order -> load( 'kilowatt' );

    $chargerType = $this -> order -> charger_connector_type -> determineChargerType();

    $chargerType == ChargerTypeEnum :: FAST
      ? $this -> updateFastChargerOrder()
      : $this -> updateLvl2ChargerOrder(); 
  }

  /**
   * Update fast charger order.
   * 
   * @return  void
   */
  private function updateFastChargerOrder()
  {

  }

  /**
   * Update Lvl 2 charger order.
   * 
   * @return  void
   */
  private function updateLvl2ChargerOrder()
  {
    $order          = $this -> order;
    $chargingStatus = $order -> charging_status;

    switch( $chargingStatus )
    {
      case OrderStatusEnum :: INITIATED :

        if( $this -> chargingHasStarted() )
        {
          $order  -> updateChargingStatus( OrderStatusEnum :: CHARGING );    
          $this   -> pay( PaymentTypeEnum :: CUT, 20 );
        }       
      break;
      
      case OrderStatusEnum :: CHARGING :

        if( $this -> shouldPay() )
        {
            $this -> pay( PaymentTypeEnum :: CUT, 20 );
        }

        if( $this -> carHasAlreadyCharged() )
        {
          $order -> updateChargingStatus( OrderStatusEnum :: CHARGED );
        } 
      break;
      
      case OrderStatusEnum :: CHARGED :
        if( $this -> isOnFine() ) 
        {
          $order -> updateChargingStatus( OrderStatusEnum :: ON_FINE); 
        }
      break;
    }

  }

  /**
   * Determine if charging has officially started.
   * 
   * @param   float $kiloWattHour
   * @return  bool
   */
  private function chargingHasStarted()
  {
    $order            = $this   -> order;
    $chargingPower    = $order  -> getChargingPower();
    $kiloWattHourLine = $this   -> kiloWattHourLine;

    return $chargingPower > $kiloWattHourLine;
  }

  /**
   * Determine if car is charged.
   * 
   * @param \App\Order $order
   * @return bool
   */
  public function carHasAlreadyCharged()
  {
    $order            = $this   -> order;
    $chargingPower    = $order  -> getChargingPower();
    $kiloWattHourLine = $this   -> kiloWattHourLine;

    return $chargingPower < $kiloWattHourLine;
  }

  /**
   * Determine if order is on fine.
   * 
   * @param \App\Order $order
   * @return bool
   */
  public function isOnFine()
  {
    $config               = Config :: first();
    $penaltyReliefMinutes = $config -> penalty_relief_minutes;

    $chargedTime          = $this -> order -> updated_at;
    $chargedTime          = Carbon :: create( $chargedTime );

    $elapsedTime          = $chargedTime -> diffInMinutes( now() );

    return $elapsedTime > $penaltyReliefMinutes;
  }

  /**
   * Determine if consumed kilowatt money is above 
   * the paid currency.
   * 
   * @return bool
   */
  private function shouldPay()
  {
    $paidMoney      = $this -> order -> countPaidMoney();
    $consumedMoney  = $this -> order -> countConsumedMoney();
    
    return  $consumedMoney > $paidMoney;
  }

  /**
   * Make transaction.
   * 
   * @param string  $paymentType
   * @param float   $amount
   */
  private function pay( $paymentType, $amount )
  {
    $userDefaultCard = $this -> order -> user_card;

    Payment :: pay( $this -> order, 20, $paymentType );

    $this -> order -> payments() -> create(
        [
          'type'          => $paymentType,
          'confirmed'     => true,
          'confirm_date'  => now(),
          'price'         => $amount,
          'prrn'          => 'SOME_PRRN',
          'trx_id'        => 'SOME_TRIX_ID',
          'user_card_id'  => $userDefaultCard -> id,
        ]
      );
  }

  /**
   * Misha's route for letting us know when 
   * the charging is completed and 
   * the cable is disconnected.
   * 
   * @param string $transaction_id
   * @return void
   */
  public function finish( $transaction_id )
  {
    
    Order :: where( 'charger_transaction_id', $transaction_id ) 
      -> update([ 'charging_status' => OrderStatusEnum :: FINISHED ]);
  }
}

