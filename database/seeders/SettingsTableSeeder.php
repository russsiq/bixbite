<?php

namespace Database\Seeders;

// Данный сид нужно разделить после (потом) по-модульно.
// Во время установки конкретного модуля заливать конкретный сид с настройками.

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsTableSeeder extends Seeder
{
    /**
     * Запустить наполнение базы данных.
     *
     * @return void
     */
    public function run()
    {
        $default = [
            'module_name' => null,
            'name' => null,
            'type' => 'string',
            'value' => null,

        ];

        DB::table('settings')->insert($this->getArticles($default));
        DB::table('settings')->insert($this->getComments($default));
        DB::table('settings')->insert($this->getFiles($default));
        DB::table('settings')->insert($this->getSystem($default));
        DB::table('settings')->insert($this->getTags($default));
        DB::table('settings')->insert($this->getUsers($default));
    }

    /**
     * [getArticles description]
     *
     * @param  array  $def
     * @return array
     */
    protected function getArticles(array $def): array
    {
        $out = [];
        $def['module_name'] = 'articles';

        // general
        array_push($out, array_merge($def, ['name' => 'views_used', 'value' => true, 'type' => 'boolean', ]));

        // meta
        array_push($out, array_merge($def, ['name' => 'meta_title', 'value' => trans('common.articles'), ]));
        array_push($out, array_merge($def, ['name' => 'meta_description', 'value' => 'Articles - content management system.', ]));
        array_push($out, array_merge($def, ['name' => 'meta_keywords', 'value' => 'articles, BixBite, CMS', ]));

        // display
        array_push($out, array_merge($def, ['name' => 'paginate', 'value' => 8, 'type' => 'integer', ]));
        array_push($out, array_merge($def, ['name' => 'order_by', 'value' => 'id', 'type' => 'select', ]));
        array_push($out, array_merge($def, ['name' => 'direction', 'value' => 'desc', 'type' => 'select', ]));
        array_push($out, array_merge($def, ['name' => 'teaser_length', 'value' => 150, 'type' => 'integer', ]));

        // create
        array_push($out, array_merge($def, ['name' => 'manual_slug', 'value' => false, 'type' => 'boolean', ]));
        array_push($out, array_merge($def, ['name' => 'manual_meta', 'value' => true, 'type' => 'boolean', ]));
        array_push($out, array_merge($def, ['name' => 'save_interval', 'value' => 120, 'type' => 'integer', ]));

        return $out;
    }

    /**
     * [getComments description]
     * @param  array  $def
     * @return array
     */
    protected function getComments(array $def): array
    {
        $out = [];
        $def['module_name'] = 'comments';

        // general
        array_push($out, array_merge($def, ['name' => 'regonly', 'value' => false, 'type' => 'boolean', ]));
        array_push($out, array_merge($def, ['name' => 'moderate', 'value' => false, 'type' => 'boolean', ]));

        // display
        array_push($out, array_merge($def, ['name' => 'nested', 'value' => true, 'type' => 'boolean', ]));

        return $out;
    }

    /**
     * [getFiles description]
     *
     * @param  array  $def
     * @return array
     */
    protected function getFiles(array $def): array
    {
        $out = [];
        $def['module_name'] = 'files';

        // general

        // image uploader
        array_push($out, array_merge($def, ['name' => 'images_quality', 'value' => 75, 'type' => 'integer', ]));
        array_push($out, array_merge($def, ['name' => 'images_is_convert', 'value' => true, 'type' => 'boolean', ]));
        array_push($out, array_merge($def, ['name' => 'images_thumb_width', 'value' => 240, 'type' => 'integer', ]));
        array_push($out, array_merge($def, ['name' => 'images_thumb_height', 'value' => 240, 'type' => 'integer', ]));
        array_push($out, array_merge($def, ['name' => 'images_small_width', 'value' => 576, 'type' => 'integer', ]));
        array_push($out, array_merge($def, ['name' => 'images_small_height', 'value' => 576, 'type' => 'integer', ]));
        array_push($out, array_merge($def, ['name' => 'images_medium_width', 'value' => 992, 'type' => 'integer', ]));
        array_push($out, array_merge($def, ['name' => 'images_medium_height', 'value' => 992, 'type' => 'integer', ]));
        array_push($out, array_merge($def, ['name' => 'images_max_width', 'value' => 1920, 'type' => 'integer', ]));
        array_push($out, array_merge($def, ['name' => 'images_max_height', 'value' => 1080, 'type' => 'integer', ]));

        return $out;
    }

    /**
     * [getTags description]
     *
     * @param  array  $def
     * @return array
     */
    protected function getTags(array $def): array
    {
        $out = [];
        $def['module_name'] = 'tags';

        // general
        array_push($out, array_merge($def, ['name' => 'delimiter', 'value' => '-', ]));

        return $out;
    }

    /**
     * [getUsers description]
     *
     * @param  array  $def
     * @return array
     */
    protected function getUsers(array $def): array
    {
        $out = [];
        $def['module_name'] = 'users';

        // general
        array_push($out, array_merge($def, ['name' => 'auth_username', 'value' => 'name', ]));
        array_push($out, array_merge($def, ['name' => 'auth_allow_register', 'value' => false, 'type' => 'boolean', ]));

        // avatar
        array_push($out, array_merge($def, ['name' => 'avatar_max_width', 'value' => 140, 'type' => 'integer', ]));
        array_push($out, array_merge($def, ['name' => 'avatar_max_height', 'value' => 140, 'type' => 'integer', ]));
        array_push($out, array_merge($def, ['name' => 'gravatar_used', 'value' => false, 'type' => 'boolean', ]));

        return $out;
    }

    /**
     * [getSystem description]
     *
     * @param  array  $def
     * @return array
     */
    protected function getSystem(array $def): array
    {
        $out = [];
        $def['module_name'] = 'system';

        // general
        array_push($out, array_merge($def, ['name' => 'app_name', 'value' => env('APP_NAME'), ]));
        array_push($out, array_merge($def, ['name' => 'app_url', 'value' => env('APP_URL'), ]));

        // meta
        array_push($out, array_merge($def, ['name' => 'meta_title', 'value' => env('APP_NAME'), ]));
        array_push($out, array_merge($def, ['name' => 'meta_title_delimiter', 'value' => ' — ', ]));
        array_push($out, array_merge($def, ['name' => 'meta_title_reverse', 'value' => false, 'type' => 'boolean', ]));
        array_push($out, array_merge($def, ['name' => 'meta_description', 'value' => 'BixBite - Content Management System', ]));
        array_push($out, array_merge($def, ['name' => 'meta_keywords', 'value' => 'BixBite CMS, BBCMS, CMS', ]));

        // organization
        array_push($out, array_merge($def, ['name' => 'org_name', 'value' => env('ORG_NAME', '—'), 'type' => 'string', ]));
        array_push($out, array_merge($def, ['name' => 'org_address_locality', 'value' => env('ORG_ADDRESS_LOCALITY', '—'), 'type' => 'string', ]));
        array_push($out, array_merge($def, ['name' => 'org_address_street', 'value' => env('ORG_ADDRESS_STREET', '—'), 'type' => 'string', ]));
        array_push($out, array_merge($def, ['name' => 'org_contact_telephone', 'value' => env('ORG_CONTACT_TELEPHONE', '—'), 'type' => 'string', ]));
        array_push($out, array_merge($def, ['name' => 'org_contact_email', 'value' => env('ORG_CONTACT_EMAIL', '—'), 'type' => 'email', ]));

        // security
        array_push($out, array_merge($def, ['name' => 'lock', 'value' => false, 'type' => 'boolean', ]));
        array_push($out, array_merge($def, ['name' => 'reason', 'value' => 'Upgrading Database! Retry later.', ]));
        array_push($out, array_merge($def, ['name' => 'retry', 'value' => '3600', 'type' => 'integer', ]));

        // captcha
        array_push($out, array_merge($def, ['name' => 'captcha_used', 'value' => true, 'type' => 'boolean', ]));
        array_push($out, array_merge($def, ['name' => 'captcha_width', 'value' => 68, 'type' => 'integer', ]));
        array_push($out, array_merge($def, ['name' => 'captcha_height', 'value' => 38, 'type' => 'integer', ]));
        array_push($out, array_merge($def, ['name' => 'captcha_font_family', 'value' => 'blowbrush', ]));
        array_push($out, array_merge($def, ['name' => 'captcha_font_size', 'value' => 20, 'type' => 'integer', ]));

        // sitemap
        array_push($out, array_merge($def, ['name' => 'home_changefreq', 'value' => 'daily', ]));
        array_push($out, array_merge($def, ['name' => 'home_priority', 'value' => 0.9, 'type' => 'float', ]));
        array_push($out, array_merge($def, ['name' => 'categories_changefreq', 'value' => 'daily', ]));
        array_push($out, array_merge($def, ['name' => 'categories_priority', 'value' => 0.6, 'type' => 'float', ]));
        array_push($out, array_merge($def, ['name' => 'articles_changefreq', 'value' => 'daily', ]));
        array_push($out, array_merge($def, ['name' => 'articles_priority', 'value' => 0.4, 'type' => 'float', ]));
        array_push($out, array_merge($def, ['name' => 'amp_articles_changefreq', 'value' => 'daily', ]));

        // personalized
        array_push($out, array_merge($def, ['name' => 'app_locale', 'value' => app_locale(), ]));
        array_push($out, array_merge($def, ['name' => 'app_theme', 'value' => app_theme(), ]));
        array_push($out, array_merge($def, ['name' => 'app_dashboard', 'value' => 'default', ]));
        array_push($out, array_merge($def, ['name' => 'translite_code', 'value' => 'ru__gost_2000_b', 'type' => 'select', ]));
        array_push($out, array_merge($def, ['name' => 'homepage_personalized', 'value' => true, 'type' => 'boolean', ]));

        return $out;
    }
}
