<?php

use App\Models\Article;
use App\Models\Tag;

use Illuminate\Database\Seeder;

class TaggablesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
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
                'tag_id' => array_random($tags),
                'taggable_id' => array_random($articles),
                'taggable_type' => 'articles',
            ];
        }

        \DB::table('taggables')->insert($taggables);
    }
}
