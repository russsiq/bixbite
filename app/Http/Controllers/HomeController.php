<?php

namespace App\Http\Controllers;

use Illuminate\Container\Container;

/**
 * Контроллер домашней страницы сайта.
 */
class HomeController extends SiteController
{
    /**
     * Макет шаблонов контроллера.
     *
     * @var string
     */
    protected $template = 'home';

    /**
     * Системные настройки.
     *
     * @var object
     */
    protected $settings;

    /**
     * Экземпляр контейнера служб.
     *
     * @var Container
     */
    private $container;

    /**
     * Создать экземпляр контроллера.
     *
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
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

        return $this->container->makeWith(ArticlesController::class)->index();
    }
}
