<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Company;
use Faker\Generator as Faker;

$factory->define(Company::class, function (Faker $faker) {
    return [
        'name'                => $faker -> name,
        'identification_code' => $faker -> uuid,
        'contract_started'    => now(),
        'contract_ended'      => now(),
        'bank_account'        => $faker -> bankAccountNumber,
        'address'             => $faker -> address,
        'contract_file'       => $faker -> uuid . '.' . 'pdf',
        'contract_method'     => $faker -> name(),
        'contract_value'      => 7,
    ];
});
