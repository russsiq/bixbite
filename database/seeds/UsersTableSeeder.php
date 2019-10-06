<?php

use BBCMS\Models\User;

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Truncate used tables.
        // User::truncate();

        // Seeding used table.
        factory(User::class, 50)->create();
    }
}