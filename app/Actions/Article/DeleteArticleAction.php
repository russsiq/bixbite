<?php

namespace App\Actions\Article;

use App\Contracts\Actions\Article\DeletesArticle;
use App\Models\Article;

class DeleteArticleAction extends ArticleActionAbstract implements DeletesArticle
{
    /**
     * Delete the given article.
     *
     * @param  Article  $article
     * @return int  Remote article ID.
     */
    public function delete(Article $article): int
    {
        $this->authorize(
            'delete', $this->article = $article->fresh()
        );

        $id = $article->id;

        $article->delete();

        return $id;
    }

    /**
     * Get the validation rules that apply to the action.
     *
     * @return array
     */
    protected function rules(): array
    {
        return [
            //
        ];
    }
}
