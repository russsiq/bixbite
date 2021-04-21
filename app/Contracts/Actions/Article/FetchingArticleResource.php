<?php

namespace App\Contracts\Actions\Article;

use App\Http\Resources\V1\ArticleResource;
use App\Models\Article;

interface FetchingArticleResource
{
    public const MODEL = Article::class;

    public function fetchResource(array $query): ArticleResource;
}
