<?php

namespace App\Contracts\Actions\Tag;

use App\Models\Tag;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

interface FetchesTag
{
    /**
     * Validate query parameters and return a specified tag.
     *
     * @param  mixed  $field
     * @param  array  $input
     * @return Tag
     */
    public function fetch(mixed $field, array $input): Tag;

    /**
     * Validate query parameters and return a collection of tags.
     *
     * @param  array  $input
     * @return EloquentCollection|Paginator
     */
    public function fetchCollection(array $input): EloquentCollection|Paginator;
}
