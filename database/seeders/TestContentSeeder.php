<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TestContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->callWith(UserSeeder::class, [
            'countToSeed' => 50
        ]);

        $this->callWith(ArticleSeeder::class, [
            'countToSeed' => 120
        ]);
    }
}
