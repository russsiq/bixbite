<?php

namespace App\Http\Controllers;

// Сторонние зависимости.
use App\Models\Category;
use App\Models\Collections\CategoryCollection;

/**
 * Абстрактный класс базового контроллера.
 */
abstract class SiteController extends BaseController
{
    /**
     * Макет шаблонов контроллера.
     * @var string
     */
    protected $template;

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
     * Получить HTML-строковое представление ответа.
     * @param  string $template
     * @param  array  $vars
     * @return string
     *
     * @NB Переопределяет родительский метод.
     */
    protected function renderOutput(string $template, array $vars = []): string
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
