<?php

namespace App\Actions\Article;

use App\Contracts\Actions\Article\FetchingArticleResource as FetchingArticleResourceContract;
use App\Http\Resources\V1\ArticleCollection;
use App\Http\Resources\V1\ArticleResource;
use App\Models\Article;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\InputBag;
use Illuminate\Database\Query\Builder as QueryBuilder;

class FetchingArticleResourceAction implements FetchingArticleResourceContract
{
    /** @var EloquentBuilder|null */
    protected $builder;

    public function fetchResource(array $query): ArticleResource
    {
        dd([
            'resource_query' => $query
        ]);
    }
}

// [
//     'include' => ['user', 'comments.user', 'comments.user.atachments', 'null'],
//     'fields' => [
//         'articles' => ['id', 'title', 'created_at', 'something'],
//         'user' => ['id', 'name'],
//     ],
//     'filter' => [
//         ['column' => 'title', 'operator' => 'contains', 'query_1' => ' lorem ipsum'],
//         'match' => 'or',
//     ],
//     'sort' => ['-created_at', 'title', 'user.name'],
//     'page' => [
//         'number' => 1,
//         'size' => 8,
//     ],
// ]
