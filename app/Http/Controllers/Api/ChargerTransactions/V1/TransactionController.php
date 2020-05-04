<?php


namespace App\Http\Controllers\Api\ChargerTransactions\V1;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

use App\Enums\ChargerType as ChargerTypeEnum;
use App\Enums\OrderStatus as OrderStatusEnum;
use App\Enums\PaymentType as PaymentTypeEnum;

use App\Library\Payments\Payment;
use App\Facades\Charger;
use App\Order;

class TransactionController extends Controller
{
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
    $order = Order :: with(
        [
          'charger_connector_type',
          'payments',
          'user',
        ]
      ) 
      -> where( 'charger_transaction_id', $transaction_id ) 
      -> first();

    $order -> addKilowatt( $value );
    $order -> load( 'kilowatt' );

    $chargerType = $order -> charger_connector_type -> determineChargerType();

    $chargerType == ChargerTypeEnum :: FAST
      ? $this -> updateFastChargerOrder( $order )
      : $this -> updateLvl2ChargerOrder( $order ); 
  }

  /**
   * Update fast charger order.
   * 
   * @param   App\Order $order
   * @return  void
   */
  private function updateFastChargerOrder()
  {

  }

  /**
   * Update Lvl 2 charger order.
   * 
   * @param   App\Order $order
   * @return  void
   */
  private function updateLvl2ChargerOrder( $order )
  {

    $chargingStatus = $order -> charging_status;

    switch( $chargingStatus )
    {
      case OrderStatusEnum :: INITIATED :
        /**
         * Check if order kiloWattHour is above the line
         * and if so charge the user with 20 GEL and change 
         * status into CHARGING. 
         * if it is not above the line then pass.
         */

        
        $hasChargingStarted = $this -> hasChargingStarted( $order );

        if( $hasChargingStarted )
        {
          $userDefaultCard = $order -> user_card;

          Payment :: pay( $order, 20, PaymentTypeEnum :: CUT );

          $order -> charging_status = OrderStatusEnum :: CHARGING;
          
          $order -> payments() -> create(
              [
                'type'          => PaymentTypeEnum :: CUT,
                'confirmed'     => true,
                'confirm_date'  => now(),
                'price'         => 20,
                'prrn'          => 'SOME_PRRN',
                'trx_id'        => 'SOME_TRIX_ID',
                'user_card_id'  => $userDefaultCard -> id,
              ]
            );
        }
      break;
      
      case OrderStatusEnum :: CHARGING :
        /**
         * Check if consumed money is above the paid money, if so
         * charge the user with another 20 GEL.
         * 
         * also check if kiloWattHour is down the line. if so
         * then change status from CHARGING to CHARGED.
         */

         $shouldPay = $this -> shouldPay( $order );

         if( $shouldPay )
         {
          $userDefaultCard = $order -> user_card;

          Payment :: pay( $order, 20, PaymentTypeEnum :: CUT );

          $order -> payments() -> create(
              [
                'type'          => PaymentTypeEnum :: CUT,
                'confirmed'     => true,
                'confirm_date'  => now(),
                'price'         => 20,
                'prrn'          => 'SOME_PRRN',
                'trx_id'        => 'SOME_TRIX_ID',
                'user_card_id'  => $userDefaultCard -> id,
              ]
            );
         }

         $hasAlreadyCharged = $this -> hasAlreadyCharged( $order );
         if( $hasAlreadyCharged )
         {
           $order -> charging_status = OrderStatusEnum :: CHARGED;
           $order -> save();
         }

      break;
      
      case OrderStatusEnum :: CHARGED :
        /**
         * Check if time has exceeded fine time if so then charge
         * the user with 20 GEL and change the status to ON_FINE.
         */
      break;
      
      case OrderStatusEnum :: ON_HOLD :
        // some code
      break;
      
      case OrderStatusEnum :: ON_FINE :
        // some code
      break;
    }

  }

  /**
   * Determine if charging has officially started.
   * 
   * @param   float $kiloWattHour
   * @return  bool
   */
  private function hasChargingStarted( $order )
  {
    $chargerInfo        = Charger :: transactionInfo( $order -> charger_transaction_id );
    $kiloWattHour       = $chargerInfo -> kiloWattHour;
    $hasStarted         = $kiloWattHour > $this -> kiloWattHourLine;
    
    return $hasStarted;
  }

  /**
   * Determine if car is charged.
   * 
   * @param \App\Order $order
   * @return bool
   */
  public function hasAlreadyCharged( $order )
  {
    $chargerInfo        = Charger :: transactionInfo( $order -> charger_transaction_id );
    $kiloWattHour       = $chargerInfo -> kiloWattHour;
    $hasCharged         = $kiloWattHour < $this -> kiloWattHourLine;
    
    return $hasCharged;
  }

  /**
   * Determine if consumed kilowatt money is above 
   * the paid currency.
   * 
   * @param \App\Order $order
   * @return bool
   */
  private function shouldPay( $order )
  {
    $paidMoney      = $order -> countPaidMoney();
    $consumedMoney  = $order -> countConsumedMoney();
    
    return  $consumedMoney > $paidMoney;
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
    // $this -> logFinish( $transaction_id );    
    
    Order :: where( 'charger_transaction_id', $transaction_id ) 
      -> update([ 'charging_status' => OrderStatusEnum :: FINISHED ]);
  }
}

