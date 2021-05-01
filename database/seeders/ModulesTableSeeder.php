<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModulesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        DB::table('modules')->insert([
            ['name' => 'articles', 'title' => 'Articles', 'icon' => 'fa fa-newspaper-o', 'info' => 'No description available', 'on_mainpage' => true],
            ['name' => 'categories', 'title' => 'Categories', 'icon' => 'fa fa-folder-open-o', 'info' => 'No description available', 'on_mainpage' => true],
            ['name' => 'tags', 'title' => 'Tags', 'icon' => 'fa fa-tags', 'info' => 'No description available', 'on_mainpage' => true],
            ['name' => 'users', 'title' => 'Users', 'icon' => 'fa fa-users', 'info' => 'No description available', 'on_mainpage' => true],
            ['name' => 'comments', 'title' => 'Comments', 'icon' => 'fa fa-comments-o', 'info' => 'No description available', 'on_mainpage' => true],
            ['name' => 'polls', 'title' => 'Polls', 'icon' => 'fa fa-list-ol', 'info' => 'No description available', 'on_mainpage' => true],
            ['name' => 'attachments', 'title' => 'Filemanager', 'icon' => 'fa fa-files-o', 'info' => 'Файловый менеджер', 'on_mainpage' => true],
            ['name' => 'system', 'title' => 'System', 'icon' => 'fa fa-list-alt', 'info' => 'No description available', 'on_mainpage' => true],
            ['name' => 'themes', 'title' => 'Design', 'icon' => 'fa fa-paint-brush', 'info' => 'No description available', 'on_mainpage' => false],
            ['name' => 'x_fields', 'title' => 'XFields', 'icon' => 'fa fa-columns', 'info' => 'Extra fields', 'on_mainpage' => true],
        ]);
    }
}
