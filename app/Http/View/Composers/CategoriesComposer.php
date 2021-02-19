<?php

namespace App\Http\View\Composers;

use App\Models\Category;
use App\Models\Collections\CategoryCollection;
use Illuminate\View\View;

class CategoriesComposer
{
    /** @var CategoryCollection */
    protected static $categories;

    /**
     * Create a new categories composer.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Bind data to the view.
     *
     * @param  \Illuminate\View\View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('categories', $this->categories());
    }

    protected function categories(): CategoryCollection
    {
        return self::$categories
            ?? self::$categories = $this->resolveCategories();
    }

    protected function resolveCategories(): CategoryCollection
    {
        return Category::query()
            ->orderBy('position')
            ->get()
            ->nested();
    }
}
