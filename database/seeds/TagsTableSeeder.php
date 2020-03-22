<?php

use App\Models\Tag;

use Illuminate\Database\Seeder;

class TagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
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
