<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\User;
use Faker\Generator as Faker;

/**
 * Define the factory to generate new User model instances
 * for testing/seeding your application's database.
 */
$factory->define(User::class, function (Faker $faker) {
    $name = $faker->name;

    return [
        'name' => $name,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'role' => $faker->randomElement([
            'admin',
            'moder',
            'user',
        ]),
        'password' => $name.$name,
        'api_token' => null,
        'remember_token' => null,
    ];
});
