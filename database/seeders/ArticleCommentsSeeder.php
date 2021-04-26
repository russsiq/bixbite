<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Comment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

class ArticleCommentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $articles = Article::pluck('id')->toArray();

        foreach ($articles as $article_id) {
            Comment::factory()
                ->count(mt_rand(0, 4))
                ->create([
                    'commentable_type' => 'articles',
                    'commentable_id' => $article_id,
                ]);
        }
    }
}
