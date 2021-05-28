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
    public function run(): void
    {
        $this->call([
            UsersTableSeeder::class,
            ArticlesTableSeeder::class,
            CategoriesTableSeeder::class,
            NotesTableSeeder::class,
            TagsTableSeeder::class,
            // AttachmentsTableSeeder::class,

            ArticleCategoriesSeeder::class,
            ArticleCommentsSeeder::class,
            ArticleTagsSeeder::class
        ]);
    }
}
