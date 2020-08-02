<?php
/** cSpell:disable */


namespace App\Traits;


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
}