<?php

use BBCMS\Models\Tag;

use Faker\Generator as Faker;

/**
 * Define the factory to generate new Tag model instances
 * for testing/seeding your application's database.
 */
$factory->define(Tag::class, function (Faker $faker) {
    return [
        'title' => $faker->unique()->word,
    ];
});

// $tags = [
//     ['title' => 'Бизнес', ],
//     ['title' => 'Политика', ],
//     ['title' => 'Спорт', ],
//     ['title' => 'Мода', ],
//     ['title' => 'Развлечения', ],
//     ['title' => 'Вне категории', ],
// ];
