<?php

use App\Models\User;

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Запустить наполнение базы данных.
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
