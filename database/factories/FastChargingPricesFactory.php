<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\FastChargingPrice;
use Faker\Generator as Faker;

$factory->define(FastChargingPrice::class, function (Faker $faker) {
    return [
        'charger_connector_type_id' => $faker -> randomNumber(),
        'start_minutes'             => $faker -> randomNumber(),
        'end_minutes'               => $faker -> randomNumber(),
        'price'                     => $faker -> randomFloat(),
    ];
});
