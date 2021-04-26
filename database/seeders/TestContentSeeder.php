<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TestContentSeeder extends Seeder
{
    /**
     * Запустить наполнение базы данных.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UsersTableSeeder::class,
            ArticlesTableSeeder::class,
            CategoriesTableSeeder::class,
            TagsTableSeeder::class,
            // AtachmentsTableSeeder::class,

            ArticleCategoriesSeeder::class,
            ArticleCommentsSeeder::class,
            ArticleTagsSeeder::class
        ]);
    }
}
