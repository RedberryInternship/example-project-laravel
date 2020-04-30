<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use App\Charger;

$factory->define(Charger::class, function (Faker $faker) {
    return [
        'name' 		  => $faker->name,
        'charger_id'  => $faker->unique()->randomDigit,
        'code'	      => $faker->unique()->randomDigit,
        'description' => $faker->name,
        'location'    => $faker->name,
        'public'	  => $faker->boolean($chanceOfGettingTrue = 50),
        'active'	  => $faker->boolean($chanceOfGettingTrue = 50),
        'lat'	      => $faker->latitude($min = -90, $max = 90),
        'lng'	 	  => $faker->longitude($min = -90, $max = 90),
        'iban'		  => $faker->iban
    ];
});
