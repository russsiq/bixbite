<?php

namespace Database\Seeders;

use App\Models\Article;
use Illuminate\Database\Seeder;

class ArticlesTableSeeder extends Seeder
{
    /**
     * Запустить наполнение базы данных.
     * 
     * @return void
     */
    public function run()
    {
        // Truncate used tables.
        // Article::truncate();

        // Seeding used table.
        Article::factory()->count(55)->create();
    }
}
