<?php

use App\Models\Category;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/**
 * Define the factory to generate new Category model instances
 * for testing/seeding your application's database.
 */
$factory->define(Category::class, function (Faker $faker) {
    // $title = $faker->sentence(mt_rand(1, 2));
    $title = $faker->unique()->word;

    $date = now()
        ->subDays(mt_rand(1, 720))
        ->addSeconds(mt_rand(1, 86400))
        ->format('Y-m-d H:i:s');

    return [
        'title' => ucfirst($title),
        'slug' => Str::slug($title),
        'info' => $faker->text(mt_rand(120, 255)),
        'created_at' => $date,
        'updated_at' => $date,

    ];
});
