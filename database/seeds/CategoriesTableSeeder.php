<?php

use App\Models\Category;

use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Запустить наполнение базы данных.
     * @return void
     */
    public function run()
    {
        // Truncate used tables.
        // Category::truncate();

        // Seeding used table.
        factory(Category::class, 8)->create();
    }
}
