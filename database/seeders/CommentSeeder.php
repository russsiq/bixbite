<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class CommentSeeder extends Seeder
{
    /**
     * The number of models that should be generated.
     *
     * @const int
     */
    public const COUNT_TO_SEED = 250;

    /**
     * Run the database seeds.
     *
     * @param  int  $countToSeed
     * @return void
     */
    public function run(int $countToSeed = null): void
    {
        $comments = Comment::factory()
            ->count($countToSeed ?: self::COUNT_TO_SEED)
            ->create();
    }
}
