<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Запустить наполнение базы данных.
     *
     * @return void
     */
    public function run()
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
