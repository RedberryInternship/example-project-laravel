<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use App\Kilowatt;

$factory->define(Kilowatt::class, function (Faker $faker) {
    return [
        'order_id'          => $faker -> randomNumber(),
        'consumed'          => 0, 
        'charging_power'    => 0,
    ];
});
