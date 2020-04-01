<?php

use App\Models\Article;

use Illuminate\Database\Seeder;

class ArticlesTableSeeder extends Seeder
{
    /**
     * Запустить наполнение базы данных.
     * @return void
     */
    public function run()
    {
        // Truncate used tables.
        // Article::truncate();

        // Seeding used table.
        factory(Article::class, 55)->create();
    }
}
