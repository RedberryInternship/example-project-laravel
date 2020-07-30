<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use App\CarModel;

$factory->define(CarModel::class, function (Faker $faker) {
    return [
    	'mark_id' => 1,
        'name'    => $faker -> name
    ];
});
