<?php

namespace App\Http\Requests\Api\V1\Article;

class Store extends ArticleRequest
{
    /**
     * Подготовить данные для валидации.
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'date_at' => 'currdate',

        ]);

        return parent::prepareForValidation();
    }
}
