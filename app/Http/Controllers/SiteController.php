<?php

namespace App\Http\Controllers;

// Сторонние зависимости.
use App\Models\Category;
use App\Models\Collections\CategoryCollection;
use App\Support\PageInfo;

class SiteController extends BaseController
{
    protected $template;

    public function __construct()
    {

    }

    /**
     * Извлечь коллекцию категорий из хранилища.
     * @return CategoryCollection
     */
    protected function resolveCategories(): CategoryCollection
    {
        return cache()->rememberForever('categories', function () {
            return Category::short()
                ->includeExternal()
                ->orderByPosition()
                ->get();
        });
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
           'categories' => $this->resolveCategories(),
       ]);

        $tpl = $this->template.'.'.$template;

        if (view()->exists($this->template) and view()->exists($tpl)) {

            $mainblock = html_raw(view($tpl, $vars)->render());

            return view($this->template, compact('mainblock'))->render();
        }

        abort(404, "View named [$tpl] not exists.");
    }
}
