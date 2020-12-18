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
            CommentsTableSeeder::class,
            CategoriesTableSeeder::class,
            CategoryablesTableSeeder::class,
            TagsTableSeeder::class,
            TaggablesTableSeeder::class,

        ]);
    }
}
