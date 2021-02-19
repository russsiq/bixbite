<?php

namespace App\Http\View\Composers;

use App\Models\Category;
use App\Models\Collections\CategoryCollection;
use Illuminate\Contracts\View\View as ViewContract;

class CategoriesComposer
{
    /** @var Category */
    protected $categories;

    /** @var CategoryCollection */
    protected static $categoryCollection;

    /**
     * Create a new categories composer.
     *
     * @param Category $categories
     */
    public function __construct(Category $categories)
    {
        $this->categories = $categories;
    }

    /**
     * Bind data to the view.
     *
     * @param  ViewContract  $view
     * @return void
     */
    public function compose(ViewContract $view): void
    {
        $view->with(
            'categories', $this->categoryCollection()
        );
    }

    /**
     * Get Category Collection.
     *
     * @return CategoryCollection
     */
    protected function categoryCollection(): CategoryCollection
    {
        return self::$categoryCollection
            ?? $this->resolveCategoryCollection();
    }

    /**
     * Resolve Category Collection.
     *
     * @return CategoryCollection
     */
    protected function resolveCategoryCollection(): CategoryCollection
    {
        return self::$categoryCollection = $this->categories->query()
            ->orderBy('position')
            ->get()
            ->nested();
    }
}
