<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Config;
use Faker\Generator as Faker;

$factory->define(Config::class, function (Faker $faker) {
    return [
        'initial_charging_price' => 10,
        'next_charging_price' => 5,
        'penalty_relief_minutes' => 2,
        'penalty_price_per_minute' => 0.05,
    ];
});
