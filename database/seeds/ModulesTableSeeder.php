<?php

use Illuminate\Database\Seeder;

class ModulesTableSeeder extends Seeder
{
    /**
     * Запустить наполнение базы данных.
     * @return void
     */
    public function run()
    {
        // Insert
        \DB::table('modules')->insert([
            ['name' => 'articles', 'title' => 'Articles', 'icon' => 'fa fa-newspaper-o', 'info' => 'No description available', 'on_mainpage' => 1, ],
            ['name' => 'categories', 'title' => 'Categories', 'icon' => 'fa fa-folder-open-o', 'info' => 'No description available', 'on_mainpage' => 1, ],
            ['name' => 'tags', 'title' => 'Tags', 'icon' => 'fa fa-tags', 'info' => 'No description available', 'on_mainpage' => 1, ],
            ['name' => 'users', 'title' => 'Users', 'icon' => 'fa fa-users', 'info' => 'No description available', 'on_mainpage' => 1, ],
            ['name' => 'comments', 'title' => 'Comments', 'icon' => 'fa fa-comments-o', 'info' => 'No description available', 'on_mainpage' => 1, ],
            ['name' => 'polls', 'title' => 'Polls', 'icon' => 'fa fa-list-ol', 'info' => 'No description available', 'on_mainpage' => 1, ],
            ['name' => 'files', 'title' => 'Filemanager', 'icon' => 'fa fa-files-o', 'info' => 'Файловый менеджер', 'on_mainpage' => 1, ],
            ['name' => 'system', 'title' => 'System', 'icon' => 'fa fa-list-alt', 'info' => 'No description available', 'on_mainpage' => 1, ],
            ['name' => 'themes', 'title' => 'Design', 'icon' => 'fa fa-paint-brush', 'info' => 'No description available', 'on_mainpage' => null, ],
            ['name' => 'x_fields', 'title' => 'XFields', 'icon' => 'fa fa-columns', 'info' => 'Extra fields', 'on_mainpage' => 1, ],
        ]);
    }
}
