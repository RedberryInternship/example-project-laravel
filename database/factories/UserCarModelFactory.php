<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;
use App\UserCarModel;
use App\User;
use App\CarModel;
	
$factory->define(UserCarModel::class, function (Faker $faker) {
 return [
        'user_id' 			=> factory(App\User::class),
        'model_id'  		=> factory(App\CarModel::class)
    ];
});
