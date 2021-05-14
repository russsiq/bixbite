<?php

namespace App\Contracts\Actions\Article;

use App\Models\Article;

interface CreatesArticle
{
    /**
     * Validate and create a newly article.
     *
     * @param  array  $input
     * @return Article
     */
    public function create(array $input): Article;
}
