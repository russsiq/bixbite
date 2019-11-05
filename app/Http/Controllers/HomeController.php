<?php

namespace BBCMS\Http\Controllers;

class HomeController extends SiteController
{
    protected $template = 'home';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Отобразить домашнюю страницу сайта.
     */
    public function index()
    {
        pageinfo([
            'title' => setting('system.meta_title', 'BixBite'),
            'description' => setting('system.meta_description', 'BixBite - Content Management System'),
            'keywords' => setting('system.meta_keywords', 'BixBite CMS, BBCMS, CMS'),
            'onHomePage' => true,
        ]);

        if (setting('system.homepage_personalized', false)) {
            return $this->makeResponse('index');
        }

        return app(ArticlesController::class)->index();
    }
}
