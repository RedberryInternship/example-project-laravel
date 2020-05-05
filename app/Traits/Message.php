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
      'ka' => 'დაფიქსირდა შეცდომა...',
      'ru' => 'Что-то пошло не так...',
    ],
    'charger_is_not_free'                 => [
      'en' => 'The Charger is not free.',
      'ru' => 'Зарядное устройство занято.',
      'ka' => 'დამტენი დაკავებულია.',
    ],
    'charging_successfully_started'       => [
      'en' => 'Charging successfully started!',
      'ka' => 'დამუხტვა წარმატებით დაიწყო!',
      'ru' => 'Зарядка успешно началась!',
    ],
    'cant_charge'                         => [
      'en' => 'Charging couldn\'t be started.',
      'ka' => 'დამუხტვის დაწყება ვერ ხერხდება.',
      'ru' => 'Зарядка не может быть начата.',
    ],
    'offline'                             => [
      'en' => 'Charger is offline!',
      'ka' => 'დამტენი გამორთულია!',
      'ru' => 'Зарядное устройство не в сети!',
    ],
    'cant_stop_charging'                  => [
      'en' => 'Stop charging request couldn\'t be confirmed.',
      'ka' => 'დამუხტვის შეჩერების დადასტურება ვერ მოხერხდა.',
      'ru' => 'Запрос на прекращение зарядки не может быть подтвержден.',
    ],
    'charger_transaction_already_stopped' => [
      'en' => 'Charger ransaction is already finished!',
      'ka' => 'Зарядное устройство уже завершено!',
      'ru' => 'დატენვის ტრანზაქცია უკვე დასრულებულია.',
    ],
    'charging_successfully_finished'      => [
      'en' => 'Charging successfully finished!',
      'ka' => 'დამუხტვა წარმატებით დასრულდა!',
      'ru' => 'Зарядка успешно остановлена!',
    ],
  ];
}