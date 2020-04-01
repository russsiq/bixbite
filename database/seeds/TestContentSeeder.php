<?php

use Illuminate\Database\Seeder;

class TestContentSeeder extends Seeder
{
    /**
     * Запустить наполнение базы данных.
     * @return void
     */
    public function run()
    {
        $baseDir = dirname(__FILE__);

        spl_autoload_register(function ($className) use ($baseDir) {
            if (file_exists($file = $baseDir . '/database/seeds/' . $className . '.php')) {
                include($file);
                return true;
            }
        });

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
