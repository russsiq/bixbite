<?php

namespace App\Http\Requests\Api\V1\Article;

class Update extends ArticleRequest
{
    /**
     * Подготовить данные для валидации.
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $this->replace(
            $this->except('user_id')
        );

        parent::prepareForValidation();
    }
}
