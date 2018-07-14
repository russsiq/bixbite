<?php

use Illuminate\Database\Seeder;

class ModulesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Insert
        \DB::table('modules')->insert([
            ['name' => 'articles', 'title' => null, 'icon' => 'fa fa-newspaper-o', 'info' => 'No description available', 'on_mainpage' => 1, ],
            ['name' => 'categories', 'title' => null, 'icon' => 'fa fa-folder-open-o', 'info' => 'No description available', 'on_mainpage' => 1, ],
            ['name' => 'tags', 'title' => null, 'icon' => 'fa fa-tags', 'info' => 'No description available', 'on_mainpage' => 1, ],
            ['name' => 'users', 'title' => null, 'icon' => 'fa fa-users', 'info' => 'No description available', 'on_mainpage' => 1, ],
            ['name' => 'comments', 'title' => null, 'icon' => 'fa fa-comments-o', 'info' => 'No description available', 'on_mainpage' => 1, ],
            ['name' => 'polls', 'title' => null, 'icon' => 'fa fa-list-ol', 'info' => 'No description available', 'on_mainpage' => 1, ],
            ['name' => 'files', 'title' => 'filemanager', 'icon' => 'fa fa-files-o', 'info' => 'Файловый менеджер', 'on_mainpage' => 1, ],
            ['name' => 'system', 'title' => null, 'icon' => 'fa fa-list-alt', 'info' => 'No description available', 'on_mainpage' => 1, ],
            ['name' => 'themes', 'title' => 'design', 'icon' => 'fa fa-paint-brush', 'info' => 'No description available', 'on_mainpage' => null, ],
            ['name' => 'x_fields', 'title' => 'x_fields', 'icon' => 'fa fa-columns', 'info' => 'Extra fields', 'on_mainpage' => 1, ],
        ]);
    }
}
