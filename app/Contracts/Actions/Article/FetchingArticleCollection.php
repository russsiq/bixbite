<?php

namespace App\Contracts\Actions\Article;

use App\Http\Resources\V1\ArticleCollection;
use App\Models\Article;

interface FetchingArticleCollection
{
    public const MODEL = Article::class;

    public function fetchCollection(array $query): ArticleCollection;

    public function resourceTable(): string;
}
