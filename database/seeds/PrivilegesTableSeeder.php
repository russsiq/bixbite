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
            ['privilege' => 'global.admin'],
            ['privilege' => 'global.locked'],

            // ['privilege' => 'register'],
            // ['privilege' => 'login'],
            // ['privilege' => 'logout'],
            // ['privilege' => 'password.email'],
            // ['privilege' => 'password.request'],
            // ['privilege' => 'password.reset'],

            ['privilege' => 'articles.index'],
            ['privilege' => 'articles.tag'],
            ['privilege' => 'articles.category'],
            ['privilege' => 'articles.article'],

            ['privilege' => 'comment.store'],

            ['privilege' => 'admin.dashboard.index'],

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

            // ['privilege' => 'admin.files.index'],
            // ['privilege' => 'admin.files.view'],
            // ['privilege' => 'admin.files.create'],
            // ['privilege' => 'admin.files.update'],
            // ['privilege' => 'admin.files.other_update'],
            // ['privilege' => 'admin.files.delete'],

            ['privilege' => 'admin.privileges.index'],
            ['privilege' => 'admin.privileges.view'],
            ['privilege' => 'admin.privileges.create'],
            ['privilege' => 'admin.privileges.update'],
            ['privilege' => 'admin.privileges.other_update'],
            ['privilege' => 'admin.privileges.delete'],

            ['privilege' => 'admin.themes.index'],
            ['privilege' => 'admin.themes.view'],
            ['privilege' => 'admin.themes.create'],
            ['privilege' => 'admin.themes.update'],
            ['privilege' => 'admin.themes.other_update'],
            ['privilege' => 'admin.themes.delete'],

            ['privilege' => 'admin.users.index'],
            ['privilege' => 'admin.users.view'],
            ['privilege' => 'admin.users.create'],
            ['privilege' => 'admin.users.update'],
            ['privilege' => 'admin.users.other_update'],
            // ['privilege' => 'admin.users.delete'], only owner site or profile

            ['privilege' => 'admin.clear.cache'],
            ['privilege' => 'admin.clear.config'],
            ['privilege' => 'admin.clear.route'],
            ['privilege' => 'admin.clear.view'],
        ]);
    }
}
