<?php

use App\Models\Article;
use App\Models\Tag;

use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class TaggablesTableSeeder extends Seeder
{
    /**
     * Запустить наполнение базы данных.
     * @return void
     */
    public function run()
    {
        // Truncate used tables.
        // \DB::table('taggables')->truncate();

        // Seeding used table.
        $tags = Tag::pluck('id')->toArray();
        $articles = Article::pluck('id')->toArray();

        $taggables = [];

        foreach (range(1, 120) as $step) {
            $taggables[] = [
                'tag_id' => Arr::random($tags),
                'taggable_id' => Arr::random($articles),
                'taggable_type' => 'articles',
            ];
        }

        \DB::table('taggables')->insert($taggables);
    }
}
