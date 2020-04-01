<?php

use App\Models\Comment;

use Illuminate\Database\Seeder;

class CommentsTableSeeder extends Seeder
{
    /**
     * Запустить наполнение базы данных.
     * @return void
     */
    public function run()
    {
        // Truncate used tables.
        // Comment::truncate();

        // Seeding used table.
        factory(Comment::class, 50)->create();
    }
}
