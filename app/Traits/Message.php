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
    'something_went_wrong'          => [
      'en' => 'Something went wrong...',
      'ka' => 'დაფიქსირდა შეცდომა...',
      'ru' => 'Что-то пошло не так...',
    ],
    'charger_is_not_free'           => [
      'en' => 'The Charger is not free.',
      'ka' => 'Зарядное устройство занято.',
      'ru' => 'დამტენი დაკავებულია.',
    ],
    'charging_successfully_started' => [
      'en' => 'Charging successfully started!',
      'ka' => 'დამუხტვა წარმატებით დაიწყო!',
      'ru' => 'Зарядка успешно началась!',
    ],
    'cant_charge'                   => [
      'en' => 'Charging couldn\'t be started.',
      'ka' => 'დამუხტვის დაწყება ვერ ხერხდება.',
      'ru' => 'Зарядка не может быть начата.',
    ],
    'offline'                       => [
      'en' => 'Charger is offline!',
      'ka' => 'დამტენი გამორთულია!',
      'ru' => 'Зарядное устройство не в сети!',
    ]
  ];
}