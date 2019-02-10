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
            'class' => 'form-control',
            'section' => 'main',
            'fieldset' => 'general',
            'name' => null,
            'type' => 'string',
            'value' => null,
            // '{"id":"id"}'
            'params' => null,
            'html_flags' => 'required',
        ];

        \DB::table('settings')->insert($this->getArticles($default));
        \DB::table('settings')->insert($this->getComments($default));
        \DB::table('settings')->insert($this->getFiles($default));
        \DB::table('settings')->insert($this->getSystem($default));
        \DB::table('settings')->insert($this->getTags($default));
        \DB::table('settings')->insert($this->getThemes($default));
        \DB::table('settings')->insert($this->getUsers($default));
    }

    protected function getArticles(array $def): array
    {
        $out = [];
        $def['module_name'] = 'articles';
        $def['action'] = 'setting';
        $def['section'] = 'main';
        $def['fieldset'] = 'meta';
        array_push($out, array_merge($def, ['name' => 'meta_title', 'value' => __('common.articles'), ]));
        array_push($out, array_merge($def, ['name' => 'meta_description', 'value' => 'Articles - content management system.', 'type' => 'text-inline', ]));
        array_push($out, array_merge($def, ['name' => 'meta_keywords', 'value' => 'articles, BixBite, CMS', 'type' => 'text-inline', ]));

        $def['fieldset'] = 'display';
        array_push($out, array_merge($def, ['name' => 'paginate', 'value' => 8, 'type' => 'integer', ]));
        array_push($out, array_merge($def, ['name' => 'order_by', 'value' => 'id', 'type' => 'select', 'params' =>
            '{"id":"id","title":"title","created_at":"created_at","updated_at":"updated_at","votes":"votes","rating":"rating","views":"views","comments_count":"comments_count"}', ]));
        array_push($out, array_merge($def, ['name' => 'direction', 'value' => 'desc', 'type' => 'select', 'params' => '{"desc":"desc","asc":"asc"}', ]));
        array_push($out, array_merge($def, ['name' => 'teaser_length', 'value' => 150, 'type' => 'integer', ]));
        array_push($out, array_merge($def, ['name' => 'views_used', 'value' => true, 'type' => 'bool', ]));

        $def['section'] = 'create';
        $def['fieldset'] = 'general';
        array_push($out, array_merge($def, ['name' => 'manual_slug', 'value' => false, 'type' => 'bool', ]));
        array_push($out, array_merge($def, ['name' => 'manual_meta', 'value' => true, 'type' => 'bool', ]));

        return $out;
    }

    protected function getComments(array $def): array
    {
        $out = [];
        $def['module_name'] = 'comments';
        $def['action'] = 'setting';
        $def['section'] = 'main';
        $def['fieldset'] = 'general';
        array_push($out, array_merge($def, ['name' => 'regonly', 'value' => false, 'type' => 'bool', ]));
        array_push($out, array_merge($def, ['name' => 'moderate', 'value' => false, 'type' => 'bool', ]));

        $def['fieldset'] = 'display';
        array_push($out, array_merge($def, ['name' => 'nested', 'value' => true, 'type' => 'bool', ]));

        $def['section'] = 'widget';
        $def['fieldset'] = 'general';
        array_push($out, array_merge($def, ['name' => 'widget_used', 'value' => 8, 'type' => 'bool', ]));
        array_push($out, array_merge($def, ['name' => 'widget_title', 'value' => trans('comments.widget_title'), ]));
        array_push($out, array_merge($def, ['name' => 'widget_count', 'value' => 8, 'type' => 'integer', ]));
        array_push($out, array_merge($def, ['name' => 'widget_content_length', 'value' => 150, 'type' => 'integer', ]));

        return $out;
    }

    protected function getFiles(array $def): array
    {
        $out = [];
        $def['module_name'] = 'files';
        $def['action'] = 'setting';
        $def['section'] = 'main';
        $def['fieldset'] = 'general';
        array_push($out, array_merge($def, ['name' => 'nested', 'value' => false, 'type' => 'bool', ]));

        return $out;
    }

    protected function getTags(array $def): array
    {
        $out = [];
        $def['module_name'] = 'tags';
        array_push($out, array_merge($def, ['name' => 'delimiter', 'value' => '-', ]));
        array_push($out, array_merge($def, ['name' => 'reindex', 'value' => 'reindex', 'type' => 'submit', 'class' => 'btn btn-outline-primary', 'html_flags' => 'formaction="reindex" formmethod="get"', ]));

        return $out;
    }

    protected function getThemes(array $def): array
    {
        $out = [];
        $def['module_name'] = 'themes';
        array_push($out, array_merge($def, ['name' => 'home_page_personalized', 'value' => true, 'type' => 'bool', ]));

        return $out;
    }

    protected function getUsers(array $def): array
    {
        $out = [];
        $def['module_name'] = 'users';
        array_push($out, array_merge($def, ['name' => 'login_username', 'value' => 'name', 'type' => 'select', 'params' => '{"name":"name","email":"email"}', ]));

        $def['fieldset'] = 'avatar';
        array_push($out, array_merge($def, ['name' => 'avatar_max_width', 'value' => 140, 'type' => 'integer', ]));
        array_push($out, array_merge($def, ['name' => 'avatar_max_height', 'value' => 140, 'type' => 'integer', ]));
        array_push($out, array_merge($def, ['name' => 'gravatar_used', 'value' => false, 'type' => 'bool', ]));

        return $out;
    }

    protected function getSystem(array $def): array
    {
        $out = [];
        $def['module_name'] = 'system';
        array_push($out, array_merge($def, ['name' => 'app_name', 'value' => env('APP_NAME'), ]));
        array_push($out, array_merge($def, ['name' => 'app_url', 'value' => env('APP_URL'), 'type' => 'url', 'html_flags' => 'required readonly', ]));
        array_push($out, array_merge($def, ['name' => 'app_locale', 'value' => app_locale(), ]));
        array_push($out, array_merge($def, ['name' => 'app_theme', 'value' => app_theme(), 'type' => 'select-themes', ]));
        array_push($out, array_merge($def, ['name' => 'app_skin', 'value' => 'default', 'type' => 'select-skins', ]));

        $def['fieldset'] = 'meta';
        array_push($out, array_merge($def, ['name' => 'meta_title', 'value' => env('APP_NAME'), 'type' => 'string', ]));
        array_push($out, array_merge($def, ['name' => 'meta_title_delimiter', 'value' => ' — ', 'type' => 'string', ]));
        array_push($out, array_merge($def, ['name' => 'meta_title_reverse', 'value' => false, 'type' => 'bool', ]));
        array_push($out, array_merge($def, ['name' => 'meta_description', 'value' => 'BixBite - Content Management System', 'type' => 'text-inline', ]));
        array_push($out, array_merge($def, ['name' => 'meta_keywords', 'value' => 'BixBite CMS, BBCMS, CMS', 'type' => 'text-inline', ]));

        $def['fieldset'] = 'organization';
        array_push($out, array_merge($def, ['name' => 'organization', 'value' => '—', 'type' => 'string', ]));
        array_push($out, array_merge($def, ['name' => 'contact_telephone', 'value' => '—', 'type' => 'string', ]));
        array_push($out, array_merge($def, ['name' => 'contact_email', 'value' => '—', 'type' => 'email', ]));
        array_push($out, array_merge($def, ['name' => 'address_locality', 'value' => '—', 'type' => 'string', ]));
        array_push($out, array_merge($def, ['name' => 'address_street', 'value' => '—', 'type' => 'string', ]));

        $def['section'] = 'security';
        $def['fieldset'] = 'locked';
        array_push($out, array_merge($def, ['name' => 'lock', 'value' => true, 'type' => 'bool', ]));
        array_push($out, array_merge($def, ['name' => 'reason', 'value' => 'Upgrading Database! Retry later.', ]));
        array_push($out, array_merge($def, ['name' => 'retry', 'value' => '3600', 'type' => 'integer', ]));

        $def['fieldset'] = 'captcha';
        array_push($out, array_merge($def, ['name' => 'captcha_used', 'value' => true, 'type' => 'bool', ]));
        array_push($out, array_merge($def, ['name' => 'captcha_width', 'value' => 68, 'type' => 'integer', ]));
        array_push($out, array_merge($def, ['name' => 'captcha_height', 'value' => 38, 'type' => 'integer', ]));
        array_push($out, array_merge($def, ['name' => 'captcha_font_family', 'value' => 'blowbrush', 'type' => 'select-fonts', ]));
        array_push($out, array_merge($def, ['name' => 'captcha_font_size', 'value' => 20, 'type' => 'integer', ]));

        $def['section'] = 'sitemap';
        $def['fieldset'] = 'sitemap_home';
        array_push($out, array_merge($def, ['name' => 'home_changefreq', 'value' => 'daily', 'type' => 'select', 'params' =>
            '{"always":"always","hourly":"hourly","daily":"daily","weekly":"weekly","monthly":"monthly","yearly":"yearly","never":"never"}']));
        array_push($out, array_merge($def, ['name' => 'home_priority', 'value' => 0.9, 'type' => 'float', 'html_flags' => 'required step="0.1" min="0.1" max="1.0"', ]));
        $def['fieldset'] = 'sitemap_categories';
        array_push($out, array_merge($def, ['name' => 'categories_changefreq', 'value' => 'daily', 'type' => 'select', 'params' =>
            '{"always":"always","hourly":"hourly","daily":"daily","weekly":"weekly","monthly":"monthly","yearly":"yearly","never":"never"}']));
        array_push($out, array_merge($def, ['name' => 'categories_priority', 'value' => 0.6, 'type' => 'float', 'html_flags' => 'required step="0.1" min="0.1" max="1.0"', ]));
        $def['fieldset'] = 'sitemap_articles';
        array_push($out, array_merge($def, ['name' => 'articles_changefreq', 'value' => 'daily', 'type' => 'select', 'params' =>
            '{"always":"always","hourly":"hourly","daily":"daily","weekly":"weekly","monthly":"monthly","yearly":"yearly","never":"never"}']));
        array_push($out, array_merge($def, ['name' => 'articles_priority', 'value' => 0.4, 'type' => 'float', 'html_flags' => 'required step="0.1" min="0.1" max="1.0"', ]));

        return $out;
    }
}
