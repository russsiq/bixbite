<?php

namespace App\Actions\Article;

use App\Contracts\Actions\Article\CreatesArticle;
use App\Models\Article;

class CreateArticleAction extends ArticleActionAbstract implements CreatesArticle
{
    /**
     * Validate and create a newly article.
     *
     * @param  array  $input
     * @return Article
     */
    public function create(array $input): Article
    {
        $this->authorize('create', Article::class);

        $this->article = Article::create(
                $this->validate(
                    $this->prepareForValidation($input)
                )
            )
            ->fresh();

        return $this->article;
    }

    /**
     * Get the validation rules that apply to the action.
     *
     * @return array
     */
    protected function rules(): array
    {
        return array_merge(
            $this->userIdRules(), // Only when create.
            // $this->imageIdRules(), // Only when update.
            $this->stateRules(),
            $this->titleRules(),
            $this->slugRules(),
        );
    }
}
