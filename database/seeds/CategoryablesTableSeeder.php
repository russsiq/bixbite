<?php

use App\Models\Article;
use App\Models\Category;

use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class CategoryablesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Truncate used tables.
        // \DB::table('categoryables')->truncate();

        // Seeding used table.
        $categories = Category::pluck('id')->toArray();
        $articles = Article::pluck('id')->toArray();

        $categoryables = [];

        foreach ($articles as $id) {
            $categoryables[] = [
                'category_id' => Arr::random($categories),
                'categoryable_id' => $id,
                'categoryable_type' => 'articles',
            ];
        }

        \DB::table('categoryables')->insert($categoryables);
    }
}
