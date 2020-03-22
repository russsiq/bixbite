<?php

use App\Models\Article;
use App\Models\User;
use Faker\Generator as Faker;

/**
 * Define the factory to generate new Article model instances
 * for testing/seeding your application's database.
 */
$factory->define(Article::class, function (Faker $faker) {
    $title = $faker->unique()->sentence(mt_rand(4, 12));

    $content = '';
    foreach (range(1, mt_rand(8, 20)) as $step) {
        $content .= '<p>'.$faker->paragraph.'</p>';
    }

    return [
        'title' => $title,
        'slug' => str_slug($title),
        'teaser' => $faker->text(mt_rand(120, 255)),
        'content' => $content,
        'user_id' => User::inRandomOrder()->first()->id,
        // 'img' => $images[array_rand($images, 1)],
        'state' => $faker->randomElement([
            'published',
            'unpublished',
            'draft',
        ]),
        'created_at' => now()->subDays(mt_rand(1, 720))->format('Y-m-d'), // Y-m-d H:i:s
    ];
});

// $images = [
//     '{"min":"scarlet-130x90.jpg","max":"scarlet-390x205.jpg","full":"scarlet-800x445.jpg"}',
//     '{"min":"lorem11-130x90.jpg","max":"lorem11-390x205.jpg","full":"lorem11-800x445.jpg"}',
//     '{"min":"lorem23-130x90.jpg","max":"lorem23-390x205.jpg","full":"lorem23-800x445.jpg"}',
//     '{"min":"beauty-130x90.jpg","max":"beauty-390x205.jpg","full":"beauty-800x445.jpg"}',
//     '{"min":"coffee-130x90.jpg","max":"coffee-390x205.jpg","full":"coffee-800x445.jpg"}',
//     '{"min":"girl-130x90.jpg","max":"girl-390x205.jpg","full":"girl-800x445.jpg"}',
//     '{"min":"model-130x90.jpg","max":"model-390x205.jpg","full":"model-800x445.jpg"}',
//     '{"min":"relay-race-130x90.jpg","max":"relay-race-390x205.jpg","full":"relay-race-800x445.jpg"}',
// ];
