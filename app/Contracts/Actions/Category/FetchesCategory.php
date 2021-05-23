<?php

namespace App\Contracts\Actions\Category;

use App\Models\Category;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

interface FetchesCategory
{
    /**
     * Validate query parameters and return a specified category.
     *
     * @param  integer  $id
     * @param  array  $input
     * @return Category
     */
    public function fetch(int $id, array $input): Category;

    /**
     * Validate query parameters and return a collection of categories.
     *
     * @param  array  $input
     * @return EloquentCollection
     */
    public function fetchCollection(array $input): EloquentCollection;
}
