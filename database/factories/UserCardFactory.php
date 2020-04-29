<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\UserCard;
use Faker\Generator as Faker;

$factory->define(UserCard::class, function (Faker $faker) {
    return [
        'user_id'        => $faker -> randomNumber(),
        'masked_pan'     => $faker -> uuid,
        'order_index'    => $faker -> randomNumber(6),
        'transaction_id' => $faker -> uuid,
        'card_holder'    => $faker -> name,
        'default'        => !! $faker -> randomNumber(1),
        'active'         => !! $faker -> randomNumber(1),
    ];
});
