<?php

namespace App\Contracts\Actions\Article;

use App\Models\Article;
use Illuminate\Contracts\Pagination\Paginator;

interface FetchesArticle
{
    /**
     * Validate query parameters and return a specified article.
     *
     * @param  integer  $id
     * @param  array  $input
     * @return Article
     */
    public function fetch(int $id, array $input): Article;

    /**
     * Validate query parameters and return a collection of articles.
     *
     * @param  array  $input
     * @return Paginator
     */
    public function fetchCollection(array $input): Paginator;
}
