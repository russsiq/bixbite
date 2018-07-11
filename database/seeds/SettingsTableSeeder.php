<?php

// Данный сид нужно разделить после (потом) по-модульно.
// Во время установки конкретного модуля заливать конкретный сид с настройками.

use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $default = [
            'module_name' => null,
            'action' => 'setting',
            'section' => 'main',
            'fieldset' => 'general',
            'name' => null,
            'type' => 'string',
            'value' => null,
            'params' => null, // '{"id":"id"}'
            'html_flags' => 'required',
        ];

        // System module.
        $system = [];
        $default['module_name'] = 'system';
        $system[] = array_merge($default, ['name' => 'app_name', 'value' => env('APP_NAME'), ]);
        $system[] = array_merge($default, ['name' => 'app_url', 'value' => env('APP_URL'), 'type' => 'url', 'html_flags' => 'required readonly', ]);
        $system[] = array_merge($default, ['name' => 'app_locale', 'value' => app_locale(), ]);
        $system[] = array_merge($default, ['name' => 'app_theme', 'value' => app_theme(), 'type' => 'select-themes', ]);
        $system[] = array_merge($default, ['name' => 'app_skin', 'value' => 'default', 'type' => 'select-skins', ]);

        $default['fieldset'] = 'meta';
        $system[] = array_merge($default, ['name' => 'meta_title', 'value' => env('APP_NAME'), 'type' => 'string', ]);
        $system[] = array_merge($default, ['name' => 'meta_title_delimiter', 'value' => ' — ', 'type' => 'string', ]);
        $system[] = array_merge($default, ['name' => 'meta_title_reverse', 'value' => false, 'type' => 'bool', ]);
        $system[] = array_merge($default, ['name' => 'meta_description', 'value' => 'BixBite - Content Management System', 'type' => 'text-inline', ]);
        $system[] = array_merge($default, ['name' => 'meta_keywords', 'value' => 'BixBite CMS, BBCMS, CMS', 'type' => 'text-inline', ]);

        $default['section'] = 'security';
        $default['fieldset'] = 'locked';
        $system[] = array_merge($default, ['name' => 'lock', 'value' => true, 'type' => 'bool', ]);
        $system[] = array_merge($default, ['name' => 'reason', 'value' => 'Upgrading Database! Retry later.', ]);
        $system[] = array_merge($default, ['name' => 'retry', 'value' => '3600', 'type' => 'integer', ]);

        $default['fieldset'] = 'captcha';
        $system[] = array_merge($default, ['name' => 'captcha_used', 'value' => true, 'type' => 'bool', ]);
        $system[] = array_merge($default, ['name' => 'captcha_width', 'value' => 68, 'type' => 'integer', ]);
        $system[] = array_merge($default, ['name' => 'captcha_height', 'value' => 38, 'type' => 'integer', ]);
        $system[] = array_merge($default, ['name' => 'captcha_font_family', 'value' => 'blowbrush', 'type' => 'select-fonts', ]);
        $system[] = array_merge($default, ['name' => 'captcha_font_size', 'value' => 20, 'type' => 'integer', ]);

        $default['section'] = 'sitemap';
        $default['fieldset'] = 'sitemap_home';
        $system[] = array_merge($default, ['name' => 'home_changefreq', 'value' => 'daily', 'type' => 'select', 'params' =>
            '{"always":"always","hourly":"hourly","daily":"daily","weekly":"weekly","monthly":"monthly","yearly":"yearly","never":"never"}']);
        $system[] = array_merge($default, ['name' => 'home_priority', 'value' => 0.9, 'type' => 'float', 'html_flags' => 'required step="0.1" min="0.1" max="1.0"', ]);
        $default['fieldset'] = 'sitemap_categories';
        $system[] = array_merge($default, ['name' => 'categories_changefreq', 'value' => 'daily', 'type' => 'select', 'params' =>
            '{"always":"always","hourly":"hourly","daily":"daily","weekly":"weekly","monthly":"monthly","yearly":"yearly","never":"never"}']);
        $system[] = array_merge($default, ['name' => 'categories_priority', 'value' => 0.6, 'type' => 'float', 'html_flags' => 'required step="0.1" min="0.1" max="1.0"', ]);
        $default['fieldset'] = 'sitemap_articles';
        $system[] = array_merge($default, ['name' => 'articles_changefreq', 'value' => 'daily', 'type' => 'select', 'params' =>
            '{"always":"always","hourly":"hourly","daily":"daily","weekly":"weekly","monthly":"monthly","yearly":"yearly","never":"never"}']);
        $system[] = array_merge($default, ['name' => 'articles_priority', 'value' => 0.4, 'type' => 'float', 'html_flags' => 'required step="0.1" min="0.1" max="1.0"', ]);

        // Articles module.
        $articles = [];
        $default['module_name'] = 'articles';
        $default['action'] = 'setting';
        $default['section'] = 'main';
        $default['fieldset'] = 'meta';
        $articles[] = array_merge($default, ['name' => 'meta_title', 'value' => __('common.articles'), ]);
        $articles[] = array_merge($default, ['name' => 'meta_description', 'value' => 'Articles - content management system.', 'type' => 'text-inline', ]);
        $articles[] = array_merge($default, ['name' => 'meta_keywords', 'value' => 'articles, BixBite, CMS', 'type' => 'text-inline', ]);

        $default['fieldset'] = 'display';
        $articles[] = array_merge($default, ['name' => 'paginate', 'value' => 8, 'type' => 'integer', ]);
        $articles[] = array_merge($default, ['name' => 'order_by', 'value' => 'id', 'type' => 'select', 'params' =>
            '{"id":"id","title":"title","created_at":"created_at","updated_at":"updated_at","votes":"votes","rating":"rating","views":"views","comments_count":"comments_count"}', ]);
        $articles[] = array_merge($default, ['name' => 'direction', 'value' => 'desc', 'type' => 'select', 'params' => '{"desc":"desc","asc":"asc"}', ]);
        $articles[] = array_merge($default, ['name' => 'teaser_length', 'value' => 150, 'type' => 'integer', ]);

        $default['section'] = 'create';
        $default['fieldset'] = 'general';
        $articles[] = array_merge($default, ['name' => 'manual_slug', 'value' => false, 'type' => 'bool', ]);
        $articles[] = array_merge($default, ['name' => 'manual_meta', 'value' => true, 'type' => 'bool', ]);

        // Comments module.
        $comments = [];
        $default['module_name'] = 'comments';
        $default['action'] = 'setting';
        $default['section'] = 'main';
        $default['fieldset'] = 'general';
        $comments[] = array_merge($default, ['name' => 'regonly', 'value' => false, 'type' => 'bool', ]);
        $comments[] = array_merge($default, ['name' => 'moderate', 'value' => false, 'type' => 'bool', ]);

        $default['fieldset'] = 'display';
        $comments[] = array_merge($default, ['name' => 'nested', 'value' => true, 'type' => 'bool', ]);

        $default['section'] = 'widget';
        $default['fieldset'] = 'general';
        $comments[] = array_merge($default, ['name' => 'widget_used', 'value' => 8, 'type' => 'bool', ]);
        $comments[] = array_merge($default, ['name' => 'widget_title', 'value' => trans('comments.widget_title'), ]);
        $comments[] = array_merge($default, ['name' => 'widget_count', 'value' => 8, 'type' => 'integer', ]);
        $comments[] = array_merge($default, ['name' => 'widget_content_length', 'value' => 150, 'type' => 'integer', ]);

        // Files module.
        $files = [];
        $default['module_name'] = 'files';
        $default['action'] = 'setting';
        $default['section'] = 'main';
        $default['fieldset'] = 'general';
        $files[] = array_merge($default, ['name' => 'nested', 'value' => false, 'type' => 'bool', ]);

        // Tags module.
        $tags = [];
        $default['module_name'] = 'tags';
        $tags[] = array_merge($default, ['name' => 'delimiter', 'value' => '-', ]);

        // Users module.
        $users = [];
        $default['module_name'] = 'users';
        $users[] = array_merge($default, ['name' => 'login_username', 'value' => 'name', 'type' => 'select', 'params' => '{"name":"name","email":"email"}', ]);

        $default['fieldset'] = 'avatar';
        $users[] = array_merge($default, ['name' => 'avatar_max_width', 'value' => 140, 'type' => 'integer', ]);
        $users[] = array_merge($default, ['name' => 'avatar_max_height', 'value' => 140, 'type' => 'integer', ]);
        $users[] = array_merge($default, ['name' => 'gravatar_used', 'value' => false, 'type' => 'bool', ]);

        \DB::table('settings')->insert($system);
        \DB::table('settings')->insert($articles);
        \DB::table('settings')->insert($comments);
        \DB::table('settings')->insert($files);
        \DB::table('settings')->insert($tags);
        \DB::table('settings')->insert($users);
    }
}
