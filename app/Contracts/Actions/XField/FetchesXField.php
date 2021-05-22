<?php

namespace App\Contracts\Actions\XField;

use App\Models\XField;
use Illuminate\Contracts\Pagination\Paginator;

interface FetchesXField
{
    /**
     * Validate query parameters and return a specified extra field.
     *
     * @param  integer  $id
     * @param  array  $input
     * @return XField
     */
    public function fetch(int $id, array $input): XField;

    /**
     * Validate query parameters and return a collection of extra fields.
     *
     * @param  array  $input
     * @return Paginator
     */
    public function fetchCollection(array $input): Paginator;
}
