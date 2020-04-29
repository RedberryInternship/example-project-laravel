<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\ChargingType;
use Faker\Generator as Faker;

$factory->define(ChargingType::class, function (Faker $faker) {
    return [
        'name' => $faker -> word()
    ];
});
