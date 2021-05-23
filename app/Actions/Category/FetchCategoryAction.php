<?php

namespace App\Actions\Category;

use App\Contracts\Actions\Category\FetchesCategory;
use App\Models\Category;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

class FetchCategoryAction extends CategoryActionAbstract implements FetchesCategory
{
    /**
     * Validate query parameters and return a specified category.
     *
     * @param  integer  $id
     * @param  array  $input
     * @return Category
     */
    public function fetch(int $id, array $input): Category
    {
        $this->category = Category::findOrFail($id);

        $this->authorize('view', $this->category);

        $this->category->loadCount([
                'articles',
            ])
            ->load([
                'attachments',
            ]);

        return $this->category;
    }

    /**
     * Validate query parameters and return a collection of categories.
     *
     * @param  array  $input
     * @return EloquentCollection
     */
    public function fetchCollection(array $input): EloquentCollection
    {
        $this->authorize('viewAny', Category::class);

        return Category::query()
            ->withCount([
                'articles',
            ])
            ->orderBy('position')
            ->get();
    }

    /**
     * Get the validation rules that apply to the action.
     *
     * @return array
     */
    protected function rules(): array
    {
        return array_merge(
            //
        );
    }
}
