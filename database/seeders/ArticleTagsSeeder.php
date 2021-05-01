<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class ArticleTagsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $tags = Tag::pluck('id')->toArray();
        $articles = Article::pluck('id')->toArray();

        $taggables = [];

        foreach (range(60, 120) as $step) {
            $tag_id = Arr::random($tags);
            $article_id = Arr::random($articles);

            $taggables[$tag_id.'_'.$article_id] = [
                'tag_id' => $tag_id,
                'taggable_id' => $article_id,
                'taggable_type' => 'articles',

            ];
        }

        DB::table('taggables')
            ->insert(
                $taggables
            );
    }
}
