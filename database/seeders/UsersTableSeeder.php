<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Запустить наполнение базы данных.
     *
     * @return void
     */
    public function run()
    {
        // Truncate used tables.
        // User::truncate();

        // Seeding used table.
        User::factory()->count(50)->create();
    }
}
