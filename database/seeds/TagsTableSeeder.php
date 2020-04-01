<?php

use App\Models\Tag;

use Illuminate\Database\Seeder;

class TagsTableSeeder extends Seeder
{
    /**
     * Запустить наполнение базы данных.
     * @return void
     */
    public function run()
    {
        // Truncate used tables.
        // Tag::truncate();

        // Seeding used table.
        factory(Tag::class, 15)->create();
    }
}
