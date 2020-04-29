<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Payment;
use Faker\Generator as Faker;

$factory->define(Payment::class, function (Faker $faker) {
    return [
        'order_id'      => $faker -> randomNumber(),
        'confirmed'     => false,
        'confirm_date'  => null,
        'price'         => $faker -> randomFloat(),
        'prrn'          => $faker -> uuid,
        'trx_id'        => $faker -> uuid,
        'user_card_id'  => $faker -> randomNumber(),
    ];
});
