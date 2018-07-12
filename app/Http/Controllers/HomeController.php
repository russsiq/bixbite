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
     * Show the application home page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        pageinfo([
            'title' => setting('system.meta_title', 'BixBite'),
            'description' => setting('system.meta_description', 'BixBite - Content Management System'),
            'keywords' => setting('system.meta_keywords', 'BixBite CMS, BBCMS, CMS'),
            'onHomePage' => true,
        ]);

        return $this->renderOutput('index', []);
    }
}
