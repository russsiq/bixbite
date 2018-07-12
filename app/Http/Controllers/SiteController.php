<?php

namespace BBCMS\Http\Controllers;

use BBCMS\Models\Category;
use BBCMS\Support\PageInfo;

class SiteController extends BaseController
{
    protected $template;

    public function __construct()
    {

    }

    protected function makeNavigation()
    {
        return app(Category::class)->getCachedNavigationCategories();
    }

    protected function getCategories()
    {
        return app(Category::class)->getCachedCategories();
    }

    protected function renderOutput(string $template, array $entities = [])
    {
        pageinfo([
            'categories' => $this->getCategories(),
            'navigation_categories' => $this->makeNavigation(),
        ]);

        if (view()->exists($this->template) and view()->exists($this->template . '.'. $template)) {
            $mainblock = html_raw(view($this->template . '.'. $template, $entities)->render());

            return view($this->template, compact('mainblock'))->render();
        }

        abort(404, 'View not exists.');
    }
}
