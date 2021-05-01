<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
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
