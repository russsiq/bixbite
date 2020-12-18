<?php

namespace Database\Seeders;

use App\Models\Comment;
use Illuminate\Database\Seeder;

class CommentsTableSeeder extends Seeder
{
    /**
     * Запустить наполнение базы данных.
     *
     * @return void
     */
    public function run()
    {
        // Truncate used tables.
        // Comment::truncate();

        // Seeding used table.
        Comment::factory()->count(50)->create();
    }
}
