<?php

use App\Models\Article;
use App\Models\Comment;
use App\Models\User;

use Faker\Generator as Faker;

/**
 * Define the factory to generate new Article model instances
 * for testing/seeding your application's database.
 */
$factory->define(Comment::class, function (Faker $faker) {
    $article = Article::inRandomOrder()
        ->select('id')
        ->published()
        ->first([
            'id',

        ]);

    $user = User::inRandomOrder()
        ->first([
            'id',

        ]);

    return [
        'user_id' => $user->id,
        'commentable_type' => 'articles',
        'commentable_id' => $article->id,
        'content' => $faker->paragraph(mt_rand(1, 4)),
        'is_approved' => mt_rand(0, 1),
        'created_at' => now()
            ->subDays(mt_rand(1, 60))
            ->format('Y-m-d'),
    ];
});
