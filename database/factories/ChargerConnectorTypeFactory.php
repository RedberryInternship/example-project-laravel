<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;
use App\ChargerConnectorType;

$factory->define(ChargerConnectorType::class, function (Faker $faker) {
    return [
    	'charger_id'          => $faker -> randomNumber(),
      'connector_type_id'   => $faker -> randomNumber(),
      'm_connector_type_id' => $faker -> randomNumber(),
      'max_price'           => $faker -> randomNumber(),
      'min_price'           => $faker -> randomNumber(),
    ];
});
