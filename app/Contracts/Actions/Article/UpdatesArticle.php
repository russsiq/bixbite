<?php

namespace App\Contracts\Actions\Article;

use App\Models\Article;

interface UpdatesArticle
{
    /**
     * Validate and update the given article.
     *
     * @param  Article  $article
     * @param  array  $input
     * @return Article
     */
    public function update(Article $article, array $input): Article;
}
