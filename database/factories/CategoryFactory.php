<?php

use BBCMS\Models\Category;
use Faker\Generator as Faker;

/**
 * Define the factory to generate new Category model instances
 * for testing/seeding your application's database.
 */
$factory->define(Category::class, function (Faker $faker) {
    // $title = $faker->sentence(mt_rand(1, 2));
    $title = $faker->unique()->word;

    return [
        'title' => ucfirst($title),
        'slug' => str_slug($title),
        'info' => $faker->text(mt_rand(120, 255)),
        'created_at' => now()->subDays(mt_rand(1, 720))->format('Y-m-d'),
    ];
});
