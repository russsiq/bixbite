<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        // Model::unguard();

        $this->call([
            ModulesTableSeeder::class,
            PrivilegesTableSeeder::class,
            SettingsTableSeeder::class,
        ]);

        // Model::reguard();
    }
}
