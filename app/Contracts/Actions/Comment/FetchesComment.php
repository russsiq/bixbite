<?php

namespace App\Contracts\Actions\Comment;

use App\Models\Comment;
use Illuminate\Contracts\Pagination\Paginator;

interface FetchesComment
{
    /**
     * Validate query parameters and return a specified comment.
     *
     * @param  integer  $id
     * @param  array  $input
     * @return Comment
     */
    public function fetch(int $id, array $input): Comment;

    /**
     * Validate query parameters and return a collection of comments.
     *
     * @param  array  $input
     * @return Paginator
     */
    public function fetchCollection(array $input): Paginator;
}
