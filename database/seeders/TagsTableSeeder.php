<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagsTableSeeder extends Seeder
{
    /**
     * Запустить наполнение базы данных.
     *
     * @return void
     */
    public function run()
    {
        // Truncate used tables.
        // Tag::truncate();

        // Seeding used table.
        Tag::factory()->count(15)->create();
    }
}
