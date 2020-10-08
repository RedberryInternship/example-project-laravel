<?php
/** cSpell:disable */

namespace App\Traits;

use App\Config;

trait Message
{
  /**
   * Notifications for App.
   * 
   * @var array $message
   */
  private $messages = [
    'something_went_wrong'                => [
      'en' => 'Something went wrong...',
      'ru' => 'Что-то пошло не так...',
      'ka' => 'დაფიქსირდა შეცდომა...',
    ],
    'charger_is_not_free'                 => [
      'en' => 'The Charger is not free.',
      'ru' => 'Зарядное устройство занято.',
      'ka' => 'დამტენი დაკავებულია.',
    ],
    'charging_successfully_started'       => [
      'en' => 'Charging successfully started!',
      'ru' => 'Зарядка успешно началась!',
      'ka' => 'დამუხტვა წარმატებით დაიწყო!',
    ],
    'cant_charge'                         => [
      'en' => 'Charging couldn\'t be started.',
      'ru' => 'Зарядка не может быть начата.',
      'ka' => 'დამუხტვის დაწყება ვერ ხერხდება.',
    ],
    'offline'                             => [
      'en' => 'Charger is offline!',
      'ru' => 'Зарядное устройство не в сети!',
      'ka' => 'დამტენი გამორთულია!',
    ],
    'cant_stop_charging'                  => [
      'en' => 'Stop charging request couldn\'t be confirmed.',
      'ru' => 'Запрос на прекращение зарядки не может быть подтвержден.',
      'ka' => 'დამუხტვის შეჩერების დადასტურება ვერ მოხერხდა.',
    ],
    'charger_transaction_already_stopped' => [
      'en' => 'Charger ransaction is already finished!',
      'ru' => 'Зарядное устройство уже завершено!',
      'ka' => 'დატენვის ტრანზაქცია უკვე დასრულებულია.',
    ],
    'charging_successfully_finished'      => [
      'en' => 'Charging successfully finished!',
      'ru' => 'Зарядка успешно остановлена!',
      'ka' => 'დამუხტვა წარმატებით დასრულდა!',
    ],
  ];

  /**
   * Charging complete message.
   * 
   * @return string
   */
  public function chargingCompleteMessage(): string
  {
    $sms = 'ელექტრომობილის დამუხტვა დასრულებულია / The Charging process is completed';
    return base64_encode( $sms );
  }

  /**
   * On penalty message.
   * 
   * @return string
   */
  public function onPenaltyMessage(): string
  {
    $config = Config :: first();

    $penaltyReliefMinutes = $config -> penalty_relief_minutes;
    $penaltyPricePerMinute = $config -> penalty_price_per_minute;

    $sms = 'ელექტრომობილის დამუხტვა დასრულებულია. გთხოვთ, ' . $penaltyReliefMinutes . ' წუთის განმავლობაში გამოაერთოთ სადენი, '.
      'წინააღმდეგ შემთხვევაში დაგერიცხებათ ჯარიმა: 1 წუთი - '. $penaltyPricePerMinute .' ლარი'.
      ' / The Charging process is completed. Please unplug the cable during ' . 
      $penaltyReliefMinutes . ' minutes Otherwise, you will be fined: 1 min. - ' . $penaltyPricePerMinute . ' GEL';

    return base64_encode( $sms );
  }
}