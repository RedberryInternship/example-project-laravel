<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Enums\PaymentType;
use App\Payment;
use Faker\Generator as Faker;

$paymentTypes = PaymentType::getConstantsValues();
$paymentTypesArrLength = count( $paymentTypes );

$factory->define(Payment::class, function (Faker $faker) use($paymentTypes, $paymentTypesArrLength) {
    return [
        'type'          => $paymentTypes[ $faker -> numberBetween(0, $paymentTypesArrLength - 1) ],
        'order_id'      => $faker -> randomNumber(),
        'confirmed'     => false,
        'confirm_date'  => null,
        'price'         => $faker -> randomFloat(),
        'prrn'          => $faker -> uuid,
        'trx_id'        => $faker -> uuid,
        'user_card_id'  => $faker -> randomNumber(),
    ];
});
