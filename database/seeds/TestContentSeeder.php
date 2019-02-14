<?php

// !!! NEED OPTIMIZED ...

use Faker\Factory;
use Illuminate\Database\Seeder;

class TestContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create('ru_RU');

        $users = [];
        $articles = [];
        $categories = [];
        $categoryables = [];
        $tags = [];
        $taggables = [];
        $comments = [];

        // Preparing users
        $roles = [
            'admin',
            'moder',
            'user',
        ];
        for ($i = 0; $i < 10; $i++) {
            $name = $faker->name;
            $users[] = [
                'name' => $name, 'email' => $faker->unique()->email, 'role' => $roles[array_rand($roles, 1)], 'password' => bcrypt($name),
            ];
        }

        // Preparing articles
        $images = [
            '{"min":"scarlet-130x90.jpg","max":"scarlet-390x205.jpg","full":"scarlet-800x445.jpg"}',
            '{"min":"lorem11-130x90.jpg","max":"lorem11-390x205.jpg","full":"lorem11-800x445.jpg"}',
            '{"min":"lorem23-130x90.jpg","max":"lorem23-390x205.jpg","full":"lorem23-800x445.jpg"}',
            '{"min":"beauty-130x90.jpg","max":"beauty-390x205.jpg","full":"beauty-800x445.jpg"}',
            '{"min":"coffee-130x90.jpg","max":"coffee-390x205.jpg","full":"coffee-800x445.jpg"}',
            '{"min":"girl-130x90.jpg","max":"girl-390x205.jpg","full":"girl-800x445.jpg"}',
            '{"min":"model-130x90.jpg","max":"model-390x205.jpg","full":"model-800x445.jpg"}',
            '{"min":"relay-race-130x90.jpg","max":"relay-race-390x205.jpg","full":"relay-race-800x445.jpg"}',
        ];
        for ($i = 0; $i < 10; $i++) {
            $title = $faker->sentence(4);
            $teaser = $faker->text(mt_rand(120, 255));
            $content = '';
            foreach(range(1, mt_rand(8, 12)) as $j) {
                $content .= '<p>'.$faker->paragraph.'</p>';
            }
            $articles[] = [
                'title' => $title, 'slug' => str_slug($title), 'teaser' => $teaser, 'content' => $content, 'user_id' => mt_rand(1, 9),
                'img' => $images[array_rand($images, 1)], 'state' => 'published', 'created_at' => date('Y-m-d H:i:s'),
            ];
        }

        // Preparing categories
        $categories = [
            ['title' => 'Бизнес', 'slug' => 'business', 'created_at' => date('Y-m-d H:i:s'), ],
            ['title' => 'Политика', 'slug' => 'politics', 'created_at' => date('Y-m-d H:i:s'), ],
            ['title' => 'Спорт', 'slug' => 'sports', 'created_at' => date('Y-m-d H:i:s'), ],
            ['title' => 'Мода', 'slug' => 'fashion', 'created_at' => date('Y-m-d H:i:s'), ],
            ['title' => 'Развлечения', 'slug' => 'entertainment', 'created_at' => date('Y-m-d H:i:s'), ],
            ['title' => 'Вне категории', 'slug' => 'other', 'created_at' => date('Y-m-d H:i:s'), ],
        ];

        // Preparing atach categories
        for ($i = 0; $i < 10; $i++) {
            $title = $faker->sentence(4);
            $categoryables[] = [
                'category_id' => mt_rand(1, 6), 'categoryable_type' => 'articles', 'categoryable_id' => $i + 1,
            ];
        }

        // Preparing tags
        $tags = [
            ['title' => 'Бизнес', ],
            ['title' => 'Политика', ],
            ['title' => 'Спорт', ],
            ['title' => 'Мода', ],
            ['title' => 'Развлечения', ],
            ['title' => 'Вне категории', ],
        ];

        // Preparing atach tags
        for ($i = 0; $i < 15; $i++) {
            $taggables[] = [
                'tag_id' => mt_rand(1, 6), 'taggable_type' => 'articles', 'taggable_id' => $i + 1,
            ];
        }

        // Preparing comments
        for ($i = 0; $i < 10; $i++) {
            $comments[] = [
                'user_id' => mt_rand(1, 9), 'commentable_type' => 'articles', 'commentable_id' => mt_rand(1, 9),
                'content' => $faker->paragraph(mt_rand(1, 5)), 'created_at' => date('Y-m-d H:i:s'), 'is_approved' => mt_rand(0, 1)
            ];
        }

        \DB::table('users')->insert($users);
        \DB::table('articles')->insert($articles);
        \DB::table('categories')->insert($categories);
        \DB::table('categoryables')->insert($categoryables);
        \DB::table('tags')->insert($tags);
        \DB::table('taggables')->insert($taggables);
        \DB::table('comments')->insert($comments);
    }
}
