<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\FAQ;
use Faker\Generator as Faker;

$factory->define(FAQ::class, function (Faker $faker) {
    return [
        "question" => [
            "en" => $faker -> word(),
            "ka" => $faker -> word(),
            "ru" => $faker -> word(),
        ],
        "answer" => [
            "en" => $faker -> word(),
            "ka" => $faker -> word(),
            "ru" => $faker -> word(),
        ]
    ];
});
