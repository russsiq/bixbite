<?php

use Illuminate\Database\Seeder;

class PrivilegesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Insert
        \DB::table('privileges')->insert([
            ['privilege' => 'global.locked'],
            
            ['privilege' => 'global.admin'],
            ['privilege' => 'dashboard'],

            ['privilege' => 'comment.store'],

            // ['privilege' => 'register'],
            // ['privilege' => 'login'],
            // ['privilege' => 'logout'],
            // ['privilege' => 'password.email'],
            // ['privilege' => 'password.request'],
            // ['privilege' => 'password.reset'],

            // ['privilege' => 'articles.index'],
            // ['privilege' => 'articles.tag'],
            // ['privilege' => 'articles.category'],
            // ['privilege' => 'articles.article'],

            ['privilege' => 'admin.articles.index'],
            ['privilege' => 'admin.articles.view'],
            ['privilege' => 'admin.articles.create'],
            ['privilege' => 'admin.articles.update'],
            ['privilege' => 'admin.articles.other_update'],
            ['privilege' => 'admin.articles.delete'],

            ['privilege' => 'admin.categories.index'],
            ['privilege' => 'admin.categories.view'],
            ['privilege' => 'admin.categories.create'],
            ['privilege' => 'admin.categories.update'],
            ['privilege' => 'admin.categories.other_update'],
            ['privilege' => 'admin.categories.delete'],

            ['privilege' => 'admin.comments.other_update'],
            
            // ['privilege' => 'privileges'], only owner
            // ['privilege' => 'admin.settings.modify'], only owner
            // ['privilege' => 'admin.settings.details'], only owner
            // ['privilege' => 'themes'], only owner
            // ['privilege' => 'x_fields'], only owner

            // ['privilege' => 'admin.files.index'],
            // ['privilege' => 'admin.files.view'],
            // ['privilege' => 'admin.files.create'],
            // ['privilege' => 'admin.files.update'],
            // ['privilege' => 'admin.files.other_update'],
            // ['privilege' => 'admin.files.delete'],

            ['privilege' => 'admin.users.index'],
            ['privilege' => 'admin.users.view'],
            ['privilege' => 'admin.users.create'],
            ['privilege' => 'admin.users.update'],
            ['privilege' => 'admin.users.other_update'],
            // ['privilege' => 'admin.users.delete'], only owner site or profile

            // ['privilege' => 'admin.clear.cache'],
            // ['privilege' => 'admin.clear.config'],
            // ['privilege' => 'admin.clear.route'],
            // ['privilege' => 'admin.clear.view'],
        ]);
    }
}
