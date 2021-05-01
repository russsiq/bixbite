<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class ArticleCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $categories = Category::pluck('id')->toArray();
        $articles = Article::pluck('id')->toArray();

        $categoryables = [];

        foreach ($articles as $article_id) {
            $category_id = Arr::random($categories);

            $categoryables[$category_id.'_'.$article_id] = [
                'category_id' => $category_id,
                'categoryable_id' => $article_id,
                'categoryable_type' => 'articles',
            ];
        }

        DB::table('categoryables')
            ->insert(
                $categoryables
            );
    }
}
