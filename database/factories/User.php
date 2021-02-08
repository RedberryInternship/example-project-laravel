<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        // 'id',
        // 'role_id',
        'phone_number' 		=> $faker->unique()->phoneNumber,
        'first_name' 		=> $faker->firstName,
        'last_name'  		=> $faker->lastName,
        'email' 			=> $faker->unique()->safeEmail,
        'active'            => 1,
        'verified'          => 1,
        'firebase_token'    => $faker->uuid,
        'email_verified_at' => now(),
        'password'          => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        // 'temp_password',
        'remember_token' => Str::random(10),
        // 'created_at',
        // 'updated_at',
        // 'deactivated_at',
        // 'company_id',
    ];
});
