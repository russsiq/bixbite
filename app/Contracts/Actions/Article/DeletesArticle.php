<?php

namespace App\Contracts\Actions\Article;

use App\Models\Article;

interface DeletesArticle
{
    /**
     * Delete the given article.
     *
     * @param  Article  $article
     * @return int  Remote article ID.
     */
    public function delete(Article $article): int;
}
