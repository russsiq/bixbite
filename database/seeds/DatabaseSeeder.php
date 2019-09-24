<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Model::unguard();
        
        $baseDir = dirname(__FILE__);
        
        spl_autoload_register(function ($className) use ($baseDir) {
            if (file_exists($file = $baseDir . '/database/seeds/' . $className . '.php')) {
                include($file);
                return true;
            }
        });

        $this->call([
            ModulesTableSeeder::class,
            PrivilegesTableSeeder::class,
            SettingsTableSeeder::class,
        ]);
        
        // Model::reguard();
    }
}
