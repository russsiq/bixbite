<?php

namespace Database\Seeders;

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
        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            TagSeeder::class,
            ArticleSeeder::class,
            CommentSeeder::class,
            // AtachmentSeeder::class,

            ArticleCategoriesSeeder::class,
            ArticleTagsSeeder::class
        ], true);
    }
}
