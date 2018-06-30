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
        // \Artisan::call('config:clear');

        // NEED TODO: $settings = array_merge($nullable_with_all_attribute, [...])
        \DB::table('settings')->insert([
            // System module
                // general
                ['module_name' => 'system', 'action' => 'setting', 'section' => 'main', 'fieldset' => 'general',
                    'name' => 'app_name', 'type' => 'string', 'value' => env('APP_NAME'), 'html_flags' => 'required',],
                ['module_name' => 'system', 'action' => 'setting', 'section' => 'main', 'fieldset' => 'general',
                    'name' => 'app_url', 'type' => 'url', 'value' => env('APP_URL'), 'html_flags' => 'required readonly',],
                ['module_name' => 'system', 'action' => 'setting', 'section' => 'main', 'fieldset' => 'general',
                    'name' => 'app_locale', 'type' => 'select-lang', 'value' => app_locale(), 'html_flags' => 'required',],
                ['module_name' => 'system', 'action' => 'setting', 'section' => 'main', 'fieldset' => 'general',
                    'name' => 'app_theme', 'type' => 'select-themes', 'value' => app_theme(), 'html_flags' => 'required',],
                ['module_name' => 'system', 'action' => 'setting', 'section' => 'main', 'fieldset' => 'general',
                    'name' => 'app_skin', 'type' => 'select-skins', 'value' => 'default', 'html_flags' => 'required',],
                // locked
                ['module_name' => 'system', 'action' => 'setting', 'section' => 'security', 'fieldset' => 'locked',
                    'name' => 'lock', 'type' => 'bool', 'value' => '1', 'html_flags' => '',],
                ['module_name' => 'system', 'action' => 'setting', 'section' => 'security', 'fieldset' => 'locked',
                    'name' => 'reason', 'type' => 'string', 'value' => 'Upgrading Database! Retry later.', 'html_flags' => '',],
                ['module_name' => 'system', 'action' => 'setting', 'section' => 'security', 'fieldset' => 'locked',
                    'name' => 'retry', 'type' => 'integer', 'value' => '3600', 'html_flags' => '',],
                // captcha
                ['module_name' => 'system', 'action' => 'setting', 'section' => 'security', 'fieldset' => 'captcha',
                    'name' => 'captcha_used', 'type' => 'bool', 'value' => '1', 'html_flags' => '',],
                ['module_name' => 'system', 'action' => 'setting', 'section' => 'security', 'fieldset' => 'captcha',
                    'name' => 'captcha_width', 'type' => 'integer', 'value' => '68', 'html_flags' => '',],
                ['module_name' => 'system', 'action' => 'setting', 'section' => 'security', 'fieldset' => 'captcha',
                    'name' => 'captcha_height', 'type' => 'integer', 'value' => '38', 'html_flags' => '',],
                ['module_name' => 'system', 'action' => 'setting', 'section' => 'security', 'fieldset' => 'captcha',
                    'name' => 'captcha_font_family', 'type' => 'select-fonts', 'value' => 'blowbrush', 'html_flags' => '',],
                ['module_name' => 'system', 'action' => 'setting', 'section' => 'security', 'fieldset' => 'captcha',
                    'name' => 'captcha_font_size', 'type' => 'integer', 'value' => '20', 'html_flags' => '',],
                // meta
                ['module_name' => 'system', 'action' => 'setting', 'section' => 'main', 'fieldset' => 'meta',
                    'name' => 'meta_title', 'type' => 'string', 'value' => env('APP_NAME'), 'html_flags' => '',],
                ['module_name' => 'system', 'action' => 'setting', 'section' => 'main', 'fieldset' => 'meta',
                    'name' => 'meta_title_delimiter', 'type' => 'string', 'value' => ' — ', 'html_flags' => '',],
                ['module_name' => 'system', 'action' => 'setting', 'section' => 'main', 'fieldset' => 'meta',
                    'name' => 'meta_title_reverse', 'type' => 'bool', 'value' => '0', 'html_flags' => '',],
                ['module_name' => 'system', 'action' => 'setting', 'section' => 'main', 'fieldset' => 'meta',
                    'name' => 'meta_description', 'type' => 'text-inline', 'value' => 'BixBite - Content Management System', 'html_flags' => '',],
                ['module_name' => 'system', 'action' => 'setting', 'section' => 'main', 'fieldset' => 'meta',
                    'name' => 'meta_keywords', 'type' => 'text-inline', 'value' => 'BixBite CMS, BBCMS, CMS', 'html_flags' => '',],
            ]);

        // Articles module
        \DB::table('settings')->insert([
            // general
            // meta
            ['module_name' => 'articles', 'action' => 'setting', 'section' => 'main', 'fieldset' => 'meta',
                'name' => 'meta_title', 'type' => 'string', 'value' => __('common.articles'), 'html_flags' => '', 'params' => null],
            ['module_name' => 'articles', 'action' => 'setting', 'section' => 'main', 'fieldset' => 'meta',
                'name' => 'meta_description', 'type' => 'text-inline', 'value' => 'BixBite - Content Management System', 'html_flags' => '', 'params' => null],
            ['module_name' => 'articles', 'action' => 'setting', 'section' => 'main', 'fieldset' => 'meta',
                'name' => 'meta_keywords', 'type' => 'text-inline', 'value' => 'BixBite CMS, BBCMS, CMS', 'html_flags' => '', 'params' => null],
            // display
            ['module_name' => 'articles', 'action' => 'setting', 'section' => 'main', 'fieldset' => 'display',
                'name' => 'paginate', 'type' => 'integer', 'value' => '8', 'html_flags' => '', 'params' => null],
            ['module_name' => 'articles', 'action' => 'setting', 'section' => 'main', 'fieldset' => 'display',
                'name' => 'order_by', 'type' => 'select', 'value' => 'id', 'html_flags' => '', 'params' => '{"id":"id","title":"title","created_at":"created_at","updated_at":"updated_at","votes":"votes","rating":"rating","views":"views","comments_count":"comments_count"}'],
            ['module_name' => 'articles', 'action' => 'setting', 'section' => 'main', 'fieldset' => 'display',
                'name' => 'direction', 'type' => 'select', 'value' => 'desc', 'html_flags' => '', 'params' => '{"desc":"desc","asc":"asc"}'],
            ['module_name' => 'articles', 'action' => 'setting', 'section' => 'main', 'fieldset' => 'display',
                'name' => 'teaser_length', 'type' => 'integer', 'value' => '150', 'html_flags' => '', 'params' => null],
            // create
            ['module_name' => 'articles', 'action' => 'setting', 'section' => 'create', 'fieldset' => 'general',
                'name' => 'manual_slug', 'type' => 'bool', 'value' => '0', 'html_flags' => '', 'params' => null],
            ['module_name' => 'articles', 'action' => 'setting', 'section' => 'create', 'fieldset' => 'general',
                'name' => 'manual_meta', 'type' => 'bool', 'value' => '1', 'html_flags' => '', 'params' => null],
        ]);

        // Comments module
        \DB::table('settings')->insert([
            // general
            ['module_name' => 'comments', 'action' => 'setting', 'section' => 'main', 'fieldset' => 'general',
                'name' => 'nested', 'type' => 'bool', 'value' => '1', 'html_flags' => '',],
            ['module_name' => 'comments', 'action' => 'setting', 'section' => 'main', 'fieldset' => 'general',
                'name' => 'regonly', 'type' => 'bool', 'value' => '0', 'html_flags' => '',],
            // widget
            ['module_name' => 'comments', 'action' => 'setting', 'section' => 'widget', 'fieldset' => 'general',
                'name' => 'widget_used', 'type' => 'bool', 'value' => '1', 'html_flags' => '',],
            ['module_name' => 'comments', 'action' => 'setting', 'section' => 'widget', 'fieldset' => 'general',
                'name' => 'widget_title', 'type' => 'string', 'value' => trans('comments.widget_title'), 'html_flags' => '',],
            ['module_name' => 'comments', 'action' => 'setting', 'section' => 'widget', 'fieldset' => 'general',
                'name' => 'widget_count', 'type' => 'integer', 'value' => '8', 'html_flags' => '',],
            ['module_name' => 'comments', 'action' => 'setting', 'section' => 'widget', 'fieldset' => 'general',
                'name' => 'widget_content_length', 'type' => 'integer', 'value' => '150', 'html_flags' => '',],
        ]);

        // Files module
        \DB::table('settings')->insert([
            ['module_name' => 'files', 'action' => 'setting', 'section' => 'main', 'fieldset' => 'general',
                'name' => 'nested', 'type' => 'bool', 'value' => '1', 'html_flags' => '',],
        ]);

        // Tags module
        \DB::table('settings')->insert([
            ['module_name' => 'tags', 'action' => 'setting', 'section' => 'main', 'fieldset' => 'general',
                'name' => 'delimiter', 'type' => 'string', 'value' => '-', 'html_flags' => 'required',],
        ]);

        // Users module
        \DB::table('settings')->insert([
            ['module_name' => 'users', 'action' => 'setting', 'section' => 'main', 'fieldset' => 'general',
                'name' => 'login_username', 'type' => 'select', 'value' => 'name', 'html_flags' => 'required', 'params' => '{"name":"name","email":"email"}'],

            ['module_name' => 'users', 'action' => 'setting', 'section' => 'main', 'fieldset' => 'avatar',
                'name' => 'avatar_max_width', 'type' => 'integer', 'value' => '140', 'html_flags' => 'required', 'params' => null,],
            ['module_name' => 'users', 'action' => 'setting', 'section' => 'main', 'fieldset' => 'avatar',
                'name' => 'avatar_max_height', 'type' => 'integer', 'value' => '140', 'html_flags' => 'required', 'params' => null,],

            ['module_name' => 'users', 'action' => 'setting', 'section' => 'main', 'fieldset' => 'avatar',
                'name' => 'gravatar_used', 'type' => 'bool', 'value' => '0', 'html_flags' => 'required', 'params' => null,],
        ]);

        // // Example module
        // \DB::table('settings')->insert([
        //     ['module_name' => , 'action' => , 'section' => , 'fieldset' => ,
        //         'name' => , 'type' => , 'value' => , 'params' => , 'html_flags' => ,],
        // ]);
    }
}
