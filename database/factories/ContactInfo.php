<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use App\Contact;

$factory->define(Contact::class, function (Faker $faker) {
    return [
        'address' => 'თენგიზ თაყაიშვილის ქუჩა',
        'phone' => $faker -> phoneNumber,
        'email' => $faker -> email,
        'fb_page' => 'Kim Kardashian',
        'fb_page_url' => 'https://facebook.com/Kim-Kardashian',
        'web_page' => 'Kimiko',
        'web_page_url' => 'https://kimiko.com',
    ];
});
