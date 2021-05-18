<?php

namespace App\Actions\Article;

use App\Contracts\Actions\Article\UpdatesArticle;
use App\Models\Article;
use App\Rules\Concerns\ExtraFieldsRules;

class UpdateArticleAction extends ArticleActionAbstract implements UpdatesArticle
{
    use ExtraFieldsRules;

    /**
     * Validate and update the given article.
     *
     * @param  Article  $article
     * @param  array  $input
     * @return Article
     */
    public function update(Article $article, array $input): Article
    {
        $this->authorize(
            'update', $this->article = $article
        );

        $this->article->update(
            $this->validate($input)
        );

        $this->article->load([
            'categories',
            'attachments',
            'tags',
            'user',
        ]);

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
            $this->extraFieldsRules(Article::getModel()),
            // $this->userIdRules(), // Determined once at creation.
            $this->imageIdRules(),
            $this->stateRules(),
            $this->titleRules(),
            $this->slugRules(),
            $this->teaserRules(),
            $this->contentRules(),
            $this->metaDescriptionRules(),
            $this->metaKeywordsRules(),
            $this->metaRobotsRules(),
            $this->onMainpageRules(),
            $this->isFavoriteRules(),
            $this->isPinnedRules(),
            $this->isCatpinnedRules(),
            $this->allowedCommentsRules(),
            $this->viewsRules(),
            $this->publishedAtRules(),
            $this->createdAtRules(),
            $this->updatedAtRules(),
        );
    }
}
