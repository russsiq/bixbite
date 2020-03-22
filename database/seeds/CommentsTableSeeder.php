<?php

use App\Models\Comment;

use Illuminate\Database\Seeder;

class CommentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
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
