<?php

namespace App\Http\View\Composers;

use App\Models\Category;
use App\Models\Collections\CategoryCollection;
use Illuminate\Contracts\View\View as ViewContract;

class CategoriesComposer
{
    /** @var Category */
    protected $categoryModel;

    /** @var CategoryCollection */
    protected $categoryCollection;

    /**
     * Create a new categories composer.
     *
     * @param Category $categoryModel
     */
    public function __construct(Category $categoryModel)
    {
        $this->categoryModel = $categoryModel;
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
        return $this->categoryCollection
            ?? $this->categoryCollection = $this->resolveCategoryCollection();
    }

    /**
     * Resolve Category Collection.
     *
     * @return CategoryCollection
     */
    protected function resolveCategoryCollection(): CategoryCollection
    {
        return $this->categoryModel->query()
            ->short()
            ->showInMenu()
            ->orderBy('position')
            ->get()
            ->nested();
    }
}
