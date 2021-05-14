<?php

namespace App\Actions\Article;

use App\Contracts\Actions\Article\FetchesArticle;
use App\Models\Article;
use Illuminate\Contracts\Pagination\Paginator;

class FetchArticleAction extends ArticleActionAbstract implements FetchesArticle
{
    /**
     * Validate query parameters and return a specified article.
     *
     * @param  integer  $id
     * @param  array  $input
     * @return Article
     */
    public function fetch(int $id, array $input): Article
    {
        $this->article = Article::findOrFail($id);

        $this->authorize('view', $this->article);

        $this->article->load([
            'categories',
            'attachments',
            'tags',
            'user',
        ]);

        return $this->article;
    }

    /**
     * Validate query parameters and return a collection of articles.
     *
     * @param  array  $input
     * @return Paginator
     */
    public function fetchCollection(array $input): Paginator
    {
        $this->authorize('viewAny', Article::class);

        return Article::with([
            'categories:categories.id,categories.title,categories.slug',
            'user:users.id,users.name',
        ])
            ->withCount([
                'comments',
                'attachments',
            ])
            ->advancedFilter($input);
    }

    /**
     * Get the validation rules that apply to the action.
     *
     * @return array
     */
    protected function rules(): array
    {
        return array_merge(
            //
        );
    }
}
