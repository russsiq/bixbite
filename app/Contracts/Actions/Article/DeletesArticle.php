<?php

namespace App\Contracts\Actions\Article;

use App\Models\Article;

interface DeletesArticle
{
    /**
     * Delete the given article.
     *
     * @param  Article  $article
     * @return bool
     */
    public function delete(Article $article): bool;
}
