<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\ChargingPrice;
use Faker\Generator as Faker;

$factory->define(ChargingPrice::class, function (Faker $faker) {
    return [
        'charger_connector_type_id' => $faker -> randomNumber(),
        'min_kwt'                   => $faker -> randomNumber(),
        'max_kwt'                   => $faker -> randomNumber(),
        'start_time'                => $faker -> randomNumber(),
        'end_time'                  => $faker -> randomNumber(),
        'price'                     => $faker -> randomNumber(),
    ];
});
