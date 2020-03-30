<?php

namespace App\Http\Controllers;

/**
 * Контроллер домашней страницы сайта.
 */
class HomeController extends SiteController
{
    /**
     * Макет шаблонов контроллера.
     * @var string
     */
    protected $template = 'home';

    /**
     * Системные настройки.
     * @var object
     */
    protected $settings;

    /**
     * Создать экземпляр контроллера.
     */
    public function __construct()
    {
        $this->settings = (object) setting('system');
    }

    /**
     * Отобразить домашнюю страницу сайта.
     */
    public function index()
    {
        pageinfo([
            'title' => $this->settings->meta_title ?? 'BixBite',
            'description' => $this->settings->meta_description ?? 'BixBite - Content Management System',
            'keywords' => $this->settings->meta_keywords ?? 'BixBite CMS, BBCMS, CMS',
            'onHomePage' => true,

        ]);

        if ($this->settings->homepage_personalized ?? true) {
            return $this->makeResponse('index');
        }

        return app(ArticlesController::class)->index();
    }
}
