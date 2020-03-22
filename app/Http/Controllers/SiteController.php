<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Support\PageInfo;

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

    /**
     * Render output to html string.
     *
     * @param  string $template
     * @param  array  $vars
     * @return mixed
     * @NB Overwriting the parent method.
     */
    protected function renderOutput(string $template, array $vars = [])
    {
        pageinfo([
            'categories' => $this->getCategories(),
            'navigation' => $this->makeNavigation(),
        ]);

        $tpl = $this->template.'.'.$template;

        if (view()->exists($this->template) and view()->exists($tpl)) {

            $mainblock = html_raw(view($tpl, $vars)->render());

            return view($this->template, compact('mainblock'))->render();
        }

        abort(404, "View named [$tpl] not exists.");
    }
}
