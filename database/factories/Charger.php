<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use App\Charger;

$factory->define(Charger::class, function (Faker $faker) {
    return [
        'old_id'            => $faker->randomDigit,
        'name'              => $faker->name,
        'charger_id'        => $faker->randomDigit,
        'code'	            => $faker->randomDigit,
        'description'       => $faker->name,
        'location'          => $faker->name,
        'image'             => 'storage/' . $faker->name . '.png',
        'public'	        => $faker->boolean($chanceOfGettingTrue = 50),
        // 'hidden'            => $faker->boolean($chanceOfGettingTrue = 50),
        'is_paid'           => $faker->boolean($chanceOfGettingTrue = 50),
        'penalty_enabled'   => $faker->boolean($chanceOfGettingTrue = 50),
        'kilowatt_price'    => $faker->randomDigit,
        'lat'	            => $faker->latitude($min = -90, $max = 90),
        'lng'	 	        => $faker->longitude($min = -90, $max = 90),
        'iban'		        => $faker->iban,
        'status'            => 'ACTIVE',
        'last_update'       => $faker->date(),
        // 'created_at',
        // 'updated_at',
        // 'company_id',
    ];
});
